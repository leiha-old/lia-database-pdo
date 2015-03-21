<?php

namespace Lia\Database\PdoBundle\Query\Clause;

use Lia\Database\PdoBundle\Query\QueryBase;

abstract class ClauseBase
{
    /**
     * @var QueryBase
     */
    protected $query;

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, array $args)
    {
        if (!method_exists($this->query, $name)) {
            throw new \Exception('PDO Query : Method [ ' . $name . ' ] is undefined !');
        }
        return call_user_func_array(array($this->query, $name), $args);
    }

    /**
     * @param QueryBase $query
     */
    public function __construct(QueryBase $query)
    {
        $this->query = $query;
    }

    /**
     * @return QueryBase
     */
    public function end()
    {
        return $this->query;
    }
}