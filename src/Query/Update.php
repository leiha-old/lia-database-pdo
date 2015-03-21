<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Pdo;
use Lia\Database\PdoBundle\Query\Clause\Where;
use Lia\Database\PdoBundle\Query\Clause\WhereUpdateInterface;
use Doctrine\DBAL\Connection;

class Update
    extends QueryBase
    implements UpdateInterface
{
    /**
     * @var array
     */
    private $setArray = array();

    /**
     * @var string
     */
    private $setInline = '';

    /**
     * @var Where
     */
    public $where;

    /**
     * @var bool
     */
    private $forceMode = false;

    /**
     * @param Pdo|Connection $connection
     * @param string $tableName
     */
    public function __construct(Pdo $connection, $tableName)
    {
        parent::__construct($connection, $tableName);
        $this->where = new Where($this);
    }

    /**
     * @return string
     */
    protected function getSqlAction()
    {
        return 'UPDATE';
    }

    /**
     * Add Where Clause to query
     * @param string $where
     * @param array $params
     * @return WhereUpdateInterface|UpdateInterface
     */
    public function where($where='', array $params = null){
        if($where) {
            $this->where->add($where, $params);
            return $this;
        }
        return $this->where;
    }

    /**
     * @param bool $enable
     * @return Update
     */
    public function enableForceMode($enable=true)
    {
        $this->forceMode = $enable;
        return $this;
    }

    /**
     * @return Update
     * @throws \Exception
     */
    protected function prepare(){
        $this->query = $this->getSqlAction().' '.$this->tableName;

        $set = '';
        if($this->setInline) {
            $set .= ' '.$this->parse($this->setInline);
        }

        if($this->setArray) {
            $set .= (strlen($set) ? ' , ' : ' ').$this->prepareSetArray();
        }

        if($set){
            $this->query .= ' SET '.$set;
        }

        $where = $this->where->prepare();
        if(!$where && !$this->forceMode) {
            throw new \Exception('The query has not a where clause : [ '
                .$this->query
                .' ] If you want use it like this enable force mode with method : enableForceMode'
            );
        }

        $this->query .= $where;
        return $this;
    }

    /**
     * @return array
     */
    protected function prepareSetArray()
    {
        $quoted = array();
        foreach ($this->setArray as $fieldName => $value) {
            $quoted[] = $fieldName.'='.$this->quote($fieldName, $value);
        }
        return implode(',', $quoted);
    }


    /**
     * @param string|array $set
     * @return Update
     */
    public function set($set)
    {
        return is_array($set)
            ? $this->setArray ($set)
            : $this->setInline($set)
            ;
    }

    /**
     * @param array $set
     * @return Update
     */
    public function setArray(array $set)
    {
        $this->setArray = $set;
        return $this;
    }

    /**
     * @param string $set
     * @return Update
     */
    public function setInline($set){
        if($this->setInline){
            $this->setInline .= ',';
        }
        $this->setInline .= $set;
        return $this;
    }
}