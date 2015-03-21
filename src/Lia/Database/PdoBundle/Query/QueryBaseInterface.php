<?php

namespace Lia\Database\PdoBundle\Query;


interface QueryBaseInterface
{
    /**
     * @param array $params
     * @return int
     */
    public function execute(array $params = array());

    /**
     * @return string
     */
    public function getQuery();

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param array $params
     * @return $this
     */
    public function addParams(array $params);

    /**
     * @param string $fieldName
     * @param string|number|bool|null $value
     * @return $this
     */
    public function addParam($fieldName, $value);

    /**
     * @param array $values
     * @return array
     */
    public function quoteArray(array $values);

    /**
     * @param string $fieldName
     * @param string|number|bool|null
     * @return mixed
     */
    public function quote($fieldName, $value);
}