<?php

namespace Lia\Database\PdoBundle\Parser;

use Doctrine\DBAL\Connection;
use Lia\Database\PdoBundle\Pdo;

abstract class ParserBase
    implements ParserInterface
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
     * @var array
     */
    protected $mapping = array();


    /**
     * @param Pdo $connection
     */
    public function __construct(Pdo $connection)
    {
        $this->pdo = $connection;
    }

    /**
     * @return string
     */
    abstract public function getPattern();

    /**
     * @param array $params
     * @return $this
     */
    abstract public function addParams(array $params);

    /**
     * @param array $params
     * @return \Closure
     */
    abstract protected function getCallBack(array $params = null);

    /**
     * @param string $field
     * @param string $mappedKey
     * @return $this
     */
    public function addMapping($field, $mappedKey)
    {
        $this->mapping[$mappedKey] = $field;
        return $this;
    }

    /**
     * @param array $mapping [field1 => [mappedKey1, mappedKey2, .etc..]]
     * @return $this
     */
    public function addMappingArray(array $mapping)
    {
        foreach($mapping as $field => $mappedKeys) {
            foreach($mappedKeys as $mappedKey) {
                $this->addMapping($field, $mappedKey);
            }
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $stringToParse
     * @param array $params
     * @param string $pattern
     * @return string
     */
    public function parse($stringToParse, array $params = null, $pattern='')
    {
        return preg_replace_callback(
            '/'.($pattern ? $pattern : $this->getPattern()).'/',
            $this->getCallBack($params),
            $stringToParse
        );
    }
}