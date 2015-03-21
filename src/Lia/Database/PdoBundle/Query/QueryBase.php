<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Definition;
use Lia\Database\PdoBundle\Pdo;
use Doctrine\DBAL\Connection;

abstract class QueryBase
    implements QueryBaseInterface
{
    /**
     * @var Pdo|Connection
     */
    public $pdo;

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $query = '';

    /**
     * @var Definition
     */
    public $tableDefinition;

    /**
     * @param Pdo|Connection $connection
     * @param string $tableName
     */
    public function __construct(Pdo $connection, $tableName)
    {
        $this->pdo       = $connection;
        $this->tableName = $tableName;
        $this->addTable($tableName);
    }

    /**
     * @return void
     */
    abstract protected function prepare();

    /**
     * @return string
     */
    abstract protected function getSqlAction();

//    private function table($tableName)
//    {
//        $this->tableName       = $tableName;
//        $this->tableDefinition = $this->pdo->getTableDefinition($tableName);
//        return $this;
//    }

    /**
     * @param array $params
     * @return int
     */
    public function execute(array $params = array())
    {
        if (count($params)) {
            $this->addParams($params);
        }
        return $this->pdo->exec($this->getQuery());
    }

    public function addTable($tableName)
    {
        $this->tableDefinition[$tableName] = $this->pdo->getTableDefinition($tableName);
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(){
        if(!$this->query){
            $this->prepare();
        }
        return $this->query;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function addParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * @param string $fieldName
     * @param string|number|bool|null $value
     * @return $this
     */
    public function addParam($fieldName, $value)
    {
        $this->params[$fieldName] = $value;
        return $this;
    }

    /**
     * @param array $values
     * @return array
     */
    public function quoteArray(array $values)
    {
        $quoted = array();
        foreach ($values as $fieldName => $value) {
            $quoted[$fieldName] = $this->quote($value, $fieldName);
        }
        return $quoted;
    }

    /**
     * @param string $fieldName
     * @param string|number|bool|null $value
     * @param null|int $type
     * @return mixed
     */
    public function quote($fieldName, $value, $type=null)
    {
        return $this->pdo->quote(
            $value,
            $type
                ? $type
                : $this->tableDefinition->getTypeOf($fieldName)
        );
    }

    /**
     * @param string $sqlPart
     * @return string
     */
    public function parse($sqlPart)
    {
        $obj = $this;
        return preg_replace_callback(
            '/:([a-zA-Z_]{1}[a-zA-Z_0-9]*)/',
            function ($match) use ($obj) {
                $params = $obj->getParams();
                return isset($params[$match[1]])
                    ? $obj->quote(
                        $match[1],
                        $params[$match[1]]
                    )
                    : $match[0];
            },
            $sqlPart
        );
    }
}