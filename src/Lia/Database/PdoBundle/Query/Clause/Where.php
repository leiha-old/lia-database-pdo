<?php

namespace Lia\Database\PdoBundle\Query\Clause;

class Where
    extends ClauseBase
    implements WhereInterface
{
    /**
     * @var string
     */
    protected $where = '';

    /**
     * @var string
     */
    protected $whereInline = '';

    /**
     * @return string
     */
    protected function getSqlClause()
    {
        return ' WHERE ';
    }

    /**
     * @return string
     */
    public function prepare()
    {
        $where = '';
        if($this->whereInline) {
            $where .= ' '.$this->whereInline;
            if($this->where) {
                $where .= ' AND '.$this->where;
            }
        } else if($this->where) {
            $where .= ' '.$this->where;
        }

        if($where){
            $where = $this->getSqlClause().$where;
        }

        return $where;
    }

    /**
     * @param string $where
     * @return $this
     */
    public function addInline($where)
    {
        if (strlen($this->whereInline)) {
            $this->whereInline .= ' ';
        }
        $this->whereInline .= $where;
        return $this;
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function superior($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, '>');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orSuperior($field, $mappedKey=null)
    {
        return $this->superior($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function inferior($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, '<');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orInferior($field, $mappedKey=null)
    {
        return $this->inferior($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function null($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, 'IS NULL');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orNull($field, $mappedKey=null)
    {
        return $this->null($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function notNull($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, 'IS NOT NULL');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orNotNull($field, $mappedKey=null)
    {
        return $this->notNull($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function in($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, 'IN');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orIn($field, $mappedKey=null)
    {
        return $this->in($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function notIn($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, 'NOT IN');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orNotIn($field, $mappedKey=null)
    {
        return $this->notIn($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function equal($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, '=');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orEqual($field, $mappedKey=null)
    {
        return $this->equal($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function notEqual($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, '!=');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orNotEqual($field, $mappedKey=null)
    {
        return $this->notEqual($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function like($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, 'LIKE');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orLike($field, $mappedKey=null)
    {
        return $this->like($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return Where
     */
    public function notLike($field, $mappedKey=null, $condition = 'AND')
    {
        return $this->_add($field, $mappedKey, $condition, 'NOT LIKE');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @return Where
     */
    public function orNotLike($field, $mappedKey=null)
    {
        return $this->notLike($field, $mappedKey, 'OR');
    }

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @param string $operator
     * @return $this
     */
    protected function _add($field, $mappedKey=null, $condition = 'AND', $operator = '=')
    {
        if($mappedKey) {
            $this->query->getParser()->addMapping($field, $mappedKey);
        }

        if (strlen($this->where)) {
            $this->where .= ' ' . $condition . ' ';
        }

        $this->where .= $field . ' ' . $operator . ' ';
        switch ($operator) {
            case 'IN'     :
            case 'NOT IN' :
                $this->where .= '(:implode|,:'.($mappedKey ? $mappedKey : $field).')';
                break;
            default:
                $this->where .= ':implode| '.$condition.' :'.($mappedKey ? $mappedKey : $field);
                break;
        }
        return $this;
    }
}