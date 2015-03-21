<?php

namespace Lia\Database\PdoBundle;

use Lia\Database\PdoBundle\Query\Update;
use Lia\Database\PdoBundle\Query\UpdateInterface;
use Doctrine\DBAL\Connection;
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
     * @param string $tableName
     * @param array $set
     * @param mixed $value
     * @param string $fieldName
     * @return UpdateInterface|int
     */
    public function update($tableName, array $set = array(), $value = null, $fieldName = null)
    {
        $execute = new Update($this, $tableName);

        if(count($set)) {
            $execute->setArray($set);
        }

        if(null !== $value) {
            if(null === $fieldName) {
                $fieldName = $execute->tableDefinition->getPrimaryKey();
            }
            $execute = $execute->where->equal($fieldName, $value)->end()->execute();
        }

        return $execute;
    }

    /**
     * @param string $tableName
     * @return Definition
     */
    public function getTableDefinition($tableName)
    {
        return new Definition($this, $tableName);
    }

    public function quote($value, $dataType = \PDO::PARAM_STR)
    {
        return $this->dbal->quote($value, $dataType);
    }

    public function quoteArray(array $values, $dataType = \PDO::PARAM_STR)
    {
        foreach ($values as &$value) {
            $value = $this->quote($value, $dataType);
        }
        return $values;
    }
}