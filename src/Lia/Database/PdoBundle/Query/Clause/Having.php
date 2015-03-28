<?php

namespace Lia\Database\PdoBundle\Query\Clause;

class Having
    extends Where
{
    /**
     * @return string
     */
    protected function getSqlClause()
    {
        return ' HAVING ';
    }
}