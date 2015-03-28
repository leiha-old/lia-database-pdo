<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Parser\ParserInterface;
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
    protected $setArray = array();

    /**
     * @var string
     */
    protected $setInline = '';

    /**
     * @var Where
     */
    protected $where;

    /**
     * @var bool
     */
    protected $forceMode = false;

    /**
     * @param Pdo|Connection $connection
     * @param ParserInterface $parser
     */
    public function __construct(Pdo $connection, ParserInterface $parser=null)
    {
        parent::__construct($connection, $parser);
        $this->where = new Where($this);
    }

    /**
     * @param string|array $set if an array the shape is :
     *                          <br /> ['field1'=>'mappedKey1', .etc..] or ['field1', .etc..]
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
     * @param array $set ['field1'=>'mappedKey1', .etc..] or ['field1', .etc..]
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

    /**
     * Add Where Clause to query
     * @param string $where
     * @return UpdateInterface
     */
    public function whereInline($where){
        $this->where->addInline($where);
        return $this;
    }

    /**
     * Return Where Clause Object
     * @return WhereUpdateInterface
     */
    public function where(){
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
     * @return string
     */
    protected function getSqlAction()
    {
        return 'UPDATE';
    }

    /**
     * @return Update
     * @throws \Exception
     */
    protected function prepare(){

        if(!$this->tableName) {
            throw new \Exception('The query has not a table name');
        }

        $this->query = $this->getSqlAction().' '.$this->tableName;

        // -----

        $set = '';
        if($this->setInline) {
            $set .= ' '.$this->setInline;
        }

        if($this->setArray) {
            $set .= (strlen($set) ? ' , ' : ' ').$this->prepareSetArray();
        }

        if(!$set){
            throw new \Exception('The query has not a set of fields');
        }

        $this->query .= ' SET '.$set;

        // -----

        $where = $this->where->prepare();
        if(!$where && !$this->forceMode) {
            throw new \Exception('The query has not a where clause : [ '
                .$this->query
                .' ] If you want use it like this, enable force mode with method : enableForceMode'
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
        $set = array();
        foreach ($this->setArray as $a => $b) {
            if(is_int($a)) {
                $set[] = $a.'= '.$a;
            } else {
                $set[] = $a.'= '.$b;
                $this->parser->addMapping($a, $b);
            }
        }
        return implode(',', $set);
    }
}