<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Query\Clause\WhereUpdateInterface;

interface UpdateInterface
    extends QueryBaseInterface
{
    /**
     * Add Where Clause to query
     * @param string $where
     * @param array $params
     * @return WhereUpdateInterface|UpdateInterface
     */
    public function where($where='', array $params = null);

    /**
     * @param bool $enable
     * @return UpdateInterface
     */
    public function enableForceMode($enable=true);

    /**
     * @param string|array $set
     * @return UpdateInterface
     */
    public function set($set);

    /**
     * @param array $set
     * @return UpdateInterface
     */
    public function setArray(array $set);

    /**
     * @param string $set
     * @return UpdateInterface
     */
    public function setInline($set);
}