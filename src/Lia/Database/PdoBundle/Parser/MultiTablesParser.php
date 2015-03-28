<?php

namespace Lia\Database\PdoBundle\Parser;

use Lia\Database\PdoBundle\Pdo;
use Lia\Database\PdoBundle\TableDefinition;

class MultiTableParser
    extends ParserBase
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $tableAlias;

    /**
     * @var TableDefinition[]
     */
    protected $tableDefinition;

    /**
     * @param Pdo    $connection
     */
    public function __construct(Pdo $connection)
    {
        parent::__construct($connection);
    }

    /**
     * @param string $tableName
     * @param string $tableAlias
     * @return $this
     */
    public function addTable($tableName, $tableAlias)
    {
        $this->tableDefinition[$tableAlias] = $this->pdo->getTableDefinition($tableName);
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return ':(([a-zA-Z_]{1}[a-zA-Z_0-9]*)\.([a-zA-Z_]{1}[a-zA-Z_0-9]*))';
    }
    /**
     * @param array $params
     * @return \Closure
     */
    protected function getCallBack(array $params = null)
    {
        if(!$params) {
            $params = $this->getParams();
        }

        $parser = $this;
        return function ($match) use ($parser, $params) {
            return isset($params[$match[1]])
                ? $parser->quote($match[1], $match[2], $params[$match[1]])
                : $match[0]
                ;
        };
    }

    /**
     * @param $tableAlias
     * @param array $params
     * @return $this
     */
    public function addParams($tableAlias, array $params)
    {
        foreach($params as $name => $value) {
            $this->addParam($tableAlias, $name, $value);
        }
        return $this;
    }

    /**
     * @param string $tableAlias
     * @param string $name
     * @param string|number|bool|null $value
     * @return $this
     */
    public function addParam($tableAlias, $name, $value)
    {
        $this->params[$tableAlias.'.'.$name] = $value;
        return $this;
    }

    /**
     * @param string $tableAlias
     * @param array  $values
     * @return array
     */
    public function quoteArray($tableAlias, array $values)
    {
        // TODO : Make an Exception if definition is no registered
        if(isset($this->tableDefinition[$tableAlias])){
            return $this->tableDefinition[$tableAlias]->quoteArray($values);
        }
    }

    /**
     * @param string $tableAlias
     * @param string $fieldName
     * @param string|number|bool|null $value
     * @return mixed
     */
    public function quote($tableAlias, $fieldName, $value)
    {
        // TODO : Make an Exception if definition is no registered
        if(isset($this->tableDefinition[$tableAlias])){
            return $this->tableDefinition[$tableAlias]->quote($fieldName, $value);
        }
        return $tableAlias.'.'.$fieldName;
    }
}