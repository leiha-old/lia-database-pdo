<?php

namespace Lia\Database\PdoBundle;

interface ExecuteInterface
{
    /**
     * @param string $query
     * @return $this
     */
    public function setQuery($query);

    /**
     * @param array $params
     * @param int $type
     * @return int
     */
    public function execute(array $params = array(), $type=\PDO::PARAM_STR);

    /**
     * @param string $parameter
     * @param string $value
     * @param int $dataType
     * @return $this
     */
    public function bindValue($parameter, $value, $dataType = \PDO::PARAM_STR);

    /**
     * @param string $parameter
     * @param string $value
     * @return $this
     */
    public function bindStringValue($parameter, $value);

    /**
     * @param string $parameter
     * @param string $value
     * @return $this
     */
    public function bindIntegerValue($parameter, $value);

    /**
     * @param string $parameter
     * @param string $value
     * @return $this
     */
    public function bindNullValue($parameter, $value);

    /**
     * @param string $parameter
     * @param string $value
     * @return $this
     */
    public function bindBoolValue($parameter, $value);
}