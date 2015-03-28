<?php

namespace Lia\Database\PdoBundle\Query;

use Lia\Database\PdoBundle\Parser\ParserBase;
use Lia\Database\PdoBundle\Parser\ParserInterface;
use Lia\Database\PdoBundle\Pdo;
use Doctrine\DBAL\Connection;

abstract class QueryBase
    implements QueryBaseInterface
{
    /**
     * @var Pdo|Connection
     */
    public $pdo;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $query = '';

    /**
     * @var ParserBase
     */
    protected $parser;

    /**
     * @param Pdo|Connection $connection
     * @param ParserInterface $parser
     */
    public function __construct(Pdo $connection, ParserInterface $parser=null)
    {
        $this->pdo    = $connection;
        $this->parser = $parser
            ? $parser
            : $this->pdo->getSimpleParser()
            ;
    }

    /**
     * @return void
     */
    abstract protected function prepare();

    /**
     * @return string
     */
    abstract protected function getSqlAction();

    /**
     * @param string $tableName
     * @return $this
     */
    public function table($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * @return ParserInterface
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param array $params
     * @return int
     */
    public function execute(array $params = null)
    {
        return $this->pdo->exec($this->getQuery($params));
    }

    /**
     * @param array $params
     * @return string
     */
    public function getQuery(array $params = null)
    {
        if(!$this->query) {
            $this->prepare();
        }
        return $params ? $this->parse($params) : $this->query;
    }

    /**
     * @param array $params
     * @return string
     */
    protected function parse(array $params = null)
    {
        if ($params) {
            $this->parser->addParams($params);
        }

        if($this->query) {
            return $this->parser->parse($this->query);
        }
    }
}