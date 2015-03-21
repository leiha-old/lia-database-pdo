<?php

namespace Lia\Database\PdoBundle\Query\Clause;

class Having
    extends Where
{
    protected function getSqlClause()
    {
        return ' HAVING ';
    }
}