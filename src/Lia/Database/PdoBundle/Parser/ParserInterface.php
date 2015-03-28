<?php

namespace Lia\Database\PdoBundle\Parser;


interface ParserInterface
{
    /**
     * @return array
     */
    public function getParams();

    /**
     * @param string $stringToParse
     * @param array $params
     * @param string $pattern
     * @return string
     */
    public function parse($stringToParse, array $params = null, $pattern='');

    /**
     * @param array $params
     * @return $this
     */
    public function addParams(array $params);

    /**
     * @param string$field
     * @param string $mappedKey
     * @return $this
     */
    public function addMapping($field, $mappedKey);

    /**
     * @param array $mapping
     * @return $this
     */
    public function addMappingArray(array $mapping);
}