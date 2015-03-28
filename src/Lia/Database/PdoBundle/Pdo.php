<?php

namespace Lia\Database\PdoBundle;

use Lia\Database\PdoBundle\Parser\MultiTableParser;
use Lia\Database\PdoBundle\Parser\ParserInterface;
use Lia\Database\PdoBundle\Parser\SimpleParser;
use Lia\Database\PdoBundle\Parser\TableParser;
use Lia\Database\PdoBundle\Query\Update;
use Lia\Database\PdoBundle\Query\UpdateInterface;
use Doctrine\DBAL\Connection;
use Lia\Database\PdoBundle\Query\UpdateTableInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Pdo
    //extends Connection
{
    /**
     * @var Connection
     */
    private $dbal;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param Connection $dbal
     */
    public function __construct(Connection $dbal)
    {
        $this->dbal = $dbal;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function getConnection()
    {
        return $this->dbal;
    }

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, array $args)
    {
        if (!method_exists($this->dbal, $name)) {
            throw new \Exception('PDO : Method [ ' . $name . ' ] is undefined !');
        }
        return call_user_func_array(array($this->dbal, $name), $args);
    }

    /**
     * @param string $query
     * @param array $params
     * @return ExecuteInterface|int
     */
    public function execute($query = '', array $params = array())
    {
        $execute = new Execute($this);
        if ($query) {
            $execute = $execute->setQuery($query)->execute($params);
        }
        return $execute;
    }

    /**
     * @param ParserInterface $parser
     * @return UpdateInterface
     */
    public function update(ParserInterface $parser=null)
    {
        $execute = new Update($this, $parser);
        return $execute;
    }

    /**
     * @param string $tableName
     * @param array  $set
     * @param mixed  $value
     * @param string $fieldName
     * @return UpdateTableInterface|int
     */
    public function updateTable($tableName, array $set = array(), $value = null, $fieldName = null)
    {
        $parser  = $this->getTableParser($tableName);
        $execute = $this->update($parser)
            ->table($tableName)
        ;

        if(count($set)) {
            $execute->setArray($set);
        }

        if(null !== $value) {
            if(null === $fieldName) {
                $fieldName = $parser->getTableDefinition()->getPrimaryKey();
            }

            $execute = $execute
                ->where()
                    ->equal($fieldName, $value)
                ->end()
                ->execute()
            ;
        }

        return $execute;
    }

    /**
     * @param string $tableName
     * @return TableDefinition
     */
    public function getTableDefinition($tableName)
    {
        return new TableDefinition($this, $tableName);
    }

    /**
     * @param string|number|null|bool $value
     * @param int $dataType
     * @return string
     */
    public function quote($value, $dataType = \PDO::PARAM_STR)
    {
        return $this->dbal->quote($value, $dataType);
    }

    /**
     * @param array $values
     * @param int $dataType
     * @return array
     */
    public function quoteArray(array $values, $dataType = \PDO::PARAM_STR)
    {
        foreach ($values as &$value) {
            $value = $this->quote($value, $dataType);
        }
        return $values;
    }

    /**
     * @return SimpleParser
     */
    public function getSimpleParser()
    {
        return new SimpleParser($this);
    }

    /**
     * @param string $tableName
     * @param string $tableAlias
     * @return TableParser
     */
    public function getTableParser($tableName, $tableAlias='')
    {
        return new TableParser($this, $tableName, $tableAlias);
    }

    /**
     * @return MultiTableParser
     */
    public function getMultiTableParser()
    {
        return new MultiTableParser($this);
    }
}