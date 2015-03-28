<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Parser\MultiTableParser;

interface UpdateMultiTableInterface
    extends UpdateInterface
{
    /**
     * @return MultiTableParser
     */
    public function getParser();
}