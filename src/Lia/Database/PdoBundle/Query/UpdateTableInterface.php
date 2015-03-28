<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Parser\TableParser;

interface UpdateTableInterface
    extends UpdateInterface
{
    /**
     * @return TableParser
     */
    public function getParser();
}