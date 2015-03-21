<?php

namespace Lia\Database\PdoBundle;

use Lia\Database\PdoBundle\Query\QueryBase;
use Doctrine\DBAL\Connection;

class Execute
    extends QueryBase
    implements ExecuteInterface
{
    const PARAM_FIELD = 9999;

    /**
     * @var string
     */
    protected $originalQuery = '';

    /**
     * @param Pdo|Connection $connection
     */
    public function __construct(Pdo $connection)
    {
        $this->pdo = $connection;
    }

    /**
     * @param string $query
     * @return $this
     */
    public function setQuery($query)
    {
        $this->originalQuery = $query;
        return $this;
    }

    /**
     * @return void
     */
    protected function prepare()
    {
        $obj = $this;
        $this->query = preg_replace_callback(
            '/:([a-zA-Z_]{1}[a-zA-Z_0-9]*)/',
            function ($match) use ($obj) {
                $params = $obj->getParams();
                if(isset($params[$match[1]])) {
                    if($params[$match[1]][1] == self::PARAM_FIELD) {
                        return '`'.$params[$match[1]][0].'`';
                    } else {
                        return $obj->pdo->quote(
                            $params[$match[1]][0],
                            $params[$match[1]][1]
                        );
                    }
                }
                else {
                    return $match[1];
                }
            },
            $this->originalQuery
        );
    }

    /**
     * @return string
     */
    protected function getSqlAction()
    {
        return;
    }

    public function execute(array $params = array(), $type=\PDO::PARAM_STR)
    {
        if (count($params)) {
            foreach ($params as $name => $value) {
                $this->bindValue($name, $value, $type);
            }
        }
        return $this->pdo->exec($this->getQuery());
    }

    /**
     * @param string $parameter
     * @param string $value
     * @param int $dataType
     * @return $this
     */
    public function bindValue($parameter, $value, $dataType = \PDO::PARAM_STR)
    {
        $this->params[$parameter] = array($value, $dataType);
        return $this;
    }

    /**
     * @param string $parameter
     * @param string $value
     * @return $this
     */
    public function bindStringValue($parameter, $value)
    {
        return $this->bindValue($parameter, $value);
    }

    /**
     * @param string $parameter
     * @param string $value
     * @return $this
     */
    public function bindIntegerValue($parameter, $value)
    {
        return $this->bindValue($parameter, $value, \PDO::PARAM_INT);
    }

    /**
     * @param string $parameter
     * @param string $value
     * @return $this
     */
    public function bindNullValue($parameter, $value)
    {
        return $this->bindValue($parameter, $value, \PDO::PARAM_NULL);
    }

    /**
     * @param string $parameter
     * @param string $value
     * @return $this
     */
    public function bindBoolValue($parameter, $value)
    {
        return $this->bindValue($parameter, $value, \PDO::PARAM_BOOL);
    }
}