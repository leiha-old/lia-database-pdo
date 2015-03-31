<?php

namespace Lia\Database\PdoBundle\Parser;

class SimpleParser
    extends ParserBase
{
    /**
     * @return string
     */
    public function getPattern()
    {
        return ':(?::(.[^|]+)(?:\|(.+)|):|)([a-zA-Z_]{1}[a-zA-Z_0-9]*)';
    }

    /**
     * @param array $params
     * @return \Closure
     */
    protected function getCallBack(array $params = null)
    {
        if(!$params) {
            $params = $this->getParams();
        }

        $parser = $this;
        return function ($match) use ($parser, $params) {
            if(isset($params[$match[3]])) {
                if(is_array($params[$match[3]])) {
                    switch($match[1]){
                        case 'implode' :
                            return implode(
                                $match[2],
                                $parser->quoteArray($params[$match[3]][0], $params[$match[3]][1])
                            );
                            break;
                    }
                } else {
                    return $parser->quote($params[$match[3]][0], $params[$match[3]][1]);
                }
            } else {
                return $match[0];
            }
        };
    }

    /**
     * @param array $params
     * @param int $type
     * @return $this
     */
    public function addParams(array $params, $type=\PDO::PARAM_STR)
    {
        foreach($params as $name => $value) {
            $this->params[$name] = array($value, $type);
        }
        return $this;
    }

    /**
     * @param string $name
     * @param string|number|bool|null $value
     * @param int $type
     * @return $this
     */
    public function addParam($name, $value, $type=\PDO::PARAM_STR)
    {
        $this->params[$name] = array($value, $type);
        return $this;
    }

    /**
     * @param array $values
     * @param int $dataType
     * @return array
     */
    public function quoteArray(array $values, $dataType = \PDO::PARAM_STR)
    {
        return $this->pdo->quoteArray($values, $dataType);
    }

    /**
     * @param string|number|bool|null $value
     * @param int $dataType
     * @return mixed
     */
    public function quote($value, $dataType = \PDO::PARAM_STR)
    {
        return $this->pdo->quote($value, $dataType);
    }
}