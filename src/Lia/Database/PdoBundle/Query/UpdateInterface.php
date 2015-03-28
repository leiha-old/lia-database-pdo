<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Query\Clause\WhereUpdateInterface;

interface UpdateInterface
    extends QueryBaseInterface
{
    /**
     * Add Where Clause to query
     * @param string $where
     * @return UpdateInterface
     */
    public function whereInline($where);

    /**
     * Return Where Clause Object
     * @return WhereUpdateInterface
     */
    public function where();

    /**
     * @param bool $enable
     * @return UpdateInterface
     */
    public function enableForceMode($enable=true);

    /**
     * @param string|array $set if an array the shape is :
     *                          <br /> ['field1'=>'mappedKey1', .etc..] or ['field1', .etc..]
     * @return Update
     */
    public function set($set);

    /**
     * @param array $set ['field1'=>'mappedKey1', .etc..] or ['field1', .etc..]
     * @return Update
     */
    public function setArray(array $set);

    /**
     * @param string $set
     * @return UpdateInterface
     */
    public function setInline($set);
}