<?php

namespace Lia\Database\PdoBundle\Parser;

use Lia\Database\PdoBundle\Pdo;
use Lia\Database\PdoBundle\TableDefinition;

class TableParser
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
     * @var TableDefinition
     */
    public $tableDefinition;

    /**
     * @param Pdo    $connection
     * @param string $tableName
     * @param string $tableAlias
     */
    public function __construct(Pdo $connection, $tableName, $tableAlias='')
    {
        parent::__construct($connection);

        $this->tableName       = $tableName;
        $this->tableAlias      = $tableAlias;
        $this->tableDefinition = $this->pdo->getTableDefinition($tableName);
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        $pattern = '(?::(.[^|]+)(?:\|(.+)|):|)([a-zA-Z_]{1}[a-zA-Z_0-9]*)';
        if($this->tableAlias) {
            $pattern = $this->tableAlias.'\.'.$pattern;
        }
        return ':'.$pattern;
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
            if(isset($params[$match[3]])) {
                $fieldName = isset($parser->mapping[$match[3]])
                    ? $parser->mapping[$match[3]]
                    : $match[3]
                    ;

                if(is_array($params[$match[3]])) {
                    switch($match[1]){
                        case 'implode' :
                            return implode(
                                $match[2],
                                $parser->quoteArray($params[$match[3]], $fieldName)
                            );
                            break;
                    }
                } else {
                   return $parser->quote($fieldName, $params[$match[3]]);
                }
            } else {
                return $match[0];
            }
        };
    }

    /**
     * @return TableDefinition
     */
    public function getTableDefinition(){
        return $this->tableDefinition;
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
     * @param string $name
     * @param string|number|bool|null $value
     * @return $this
     */
    public function addParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    /**
     * @param array $values
     * @param string $fieldName
     * @return array
     */
    public function quoteArray(array $values, $fieldName='')
    {
        return $this->tableDefinition->quoteArray($values, $fieldName);
    }

    /**
     * @param string $fieldName
     * @param string|number|bool|null $value
     * @return mixed
     */
    public function quote($fieldName, $value)
    {
        return $this->tableDefinition->quote($fieldName, $value);
    }
}