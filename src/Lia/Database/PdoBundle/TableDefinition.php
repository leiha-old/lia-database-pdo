<?php

namespace Lia\Database\PdoBundle;

use Doctrine\DBAL\Connection;
use Lia\KernelBundle\Cache\DirectoryCache;

class TableDefinition
{
    /**
     * @var string
     */
    private $tableName;

    /**
     * @var Pdo|Connection
     */
    private $pdo;

    /**
     * @var string
     */
    private $cacheDir = '/_pdoConfig/';

    /**
     * @var DirectoryCache
     */
    private $cache;

    /**
     * @var array
     */
    private $metaData;

    /**
     * @param Pdo $pdo
     * @param string $tableName
     * @param string $tableAlias
     */
    public function __construct(Pdo $pdo, $tableName){
        $this->pdo        = $pdo;
        $this->tableName  = $tableName;
        $this->cache      = $this->pdo->getContainer()->get('lia.factory.cache.directory');
        $this->getMetaData();
    }

    public function getPrimaryKey()
    {
        return $this->metaData['primaryKey'];
    }

    /**
     * @param string $fieldName
     * @return int
     */
    public function getTypeOf($fieldName)
    {
        if(isset($this->metaData['fields'][$fieldName]))
        {
            switch($this->metaData['fields'][$fieldName]['type']){
                case 'bool'      :
                case 'boolean'   :
                    return \PDO::PARAM_BOOL;
                    break;
                case 'int'       :
                case 'integer'   :
                case 'tinyint'   :
                case 'smallint'  :
                case 'mediumint' :
                case 'bigint'    :
                    return \PDO::PARAM_INT;
                    break;
//                case 'date'      :
//                case 'time'      :
//                case 'datetime'  :
//                case 'timestamp' :
//                case 'year'    :
//                case 'double'    :
//                case 'float'     :
//                case 'decimal'   :
//                case 'numeric'   :
//                case 'char'      :
//                case 'varchar'   :
//                case 'binary'    :
//                case 'varbinary' :
//                case 'blob'      :
//                case 'text'      :
//                case 'enum'      :
//                case 'set'       :
//                    break;
            }
        }
        return \PDO::PARAM_STR;
    }

    /**
     * @param array $values
     * @param string $fieldName
     * @return array
     */
    public function quoteArray(array $values, $fieldName='')
    {
        $quoted = array();
        if(!$fieldName) {
            foreach ($values as $fieldName => $value) {
                $quoted[$fieldName] = $this->quote($fieldName, $value);
            }
        } else {
            foreach ($values as $value) {
                $quoted[] = $this->quote($fieldName, $value);
            }
        }
        return $quoted;
    }

    /**
     * @param string $fieldName
     * @param string|number|bool|null $value
     * @return mixed
     */
    public function quote($fieldName, $value)
    {
        return $this->pdo->quote($value, $this->getTypeOf($fieldName));
    }

    /**
     * @return array
     */
    private function getMetaData()
    {
        if(!count($this->metaData)){
            if(!$this->metaData = $this->cache->get($this->cacheDir.$this->tableName)){
                $this->getMetaDataOnDb();
            }
        }
        return $this->metaData;
    }

    /**
     * @return int
     */
    private function getMetaDataOnDb()
    {
        /* @var \Doctrine\DBAL\Driver\PDOStatement $result */
        $result = $this->pdo->query('SHOW COLUMNS FROM '.$this->tableName)->fetchAll();

        $columns = array();
        foreach($result as $column){
            $fieldName = $column['Field'];

            preg_match('#([a-z]+)(?:\((.*)\)|)#', $column['Type'], $matches);

            $columns['fields'][$fieldName] = array(
                'type' => $matches[1],
                'size' => isset($matches[2]) ? $matches[2] : ''
            );

            if('PRI' == $column['Key']){
                $columns['primaryKey'] = $fieldName;
            }
        }
        $this->metaData = $columns;
        return $this->cache->set($this->cacheDir.$this->tableName, $this->metaData);

    }
}