<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Parser\ParserBase;

interface QueryBaseInterface
{
    /**
     * @param array $params
     * @return int
     */
    public function execute(array $params = null);

    /**
     * @param array $params
     * @return string
     */
    public function getQuery(array $params = null);

    /**
     * @return ParserBase
     */
    public function getParser();

    /**
     * @param string $tableName
     * @return $this
     */
    public function table($tableName);
}