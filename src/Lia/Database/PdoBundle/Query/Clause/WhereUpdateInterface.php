<?php

namespace Lia\Database\PdoBundle\Query\Clause;

use Lia\Database\PdoBundle\Query\UpdateInterface;

interface WhereUpdateInterface
    extends WhereInterface
{
    /**
     * Return directly Object of query (without call magical __call)
     * @return UpdateInterface
     */
    public function end();
}