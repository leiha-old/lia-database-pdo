<?php

namespace Lia\Database\PdoBundle\Query\Clause;

use Doctrine\DBAL\Connection;

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
            $where = $this->getSqlClause().$this->query->parse($where);
        }

        return $where;
    }

    /**
     * @param string $where
     * @param array $params
     * @return $this
     */
    public function add($where, array $params = null)
    {
        if (strlen($this->whereInline)) {
            $this->whereInline = ' ';
        }
        $this->whereInline .= $where;

        if(is_array($params)) {
            $this->query->addParams($params);
        }
        return $this;
    }

    /**
     * @param string $field
     * @param bool $value
     * @param string $condition
     * @return Where
     */
    public function superior(
        $field,
        $value = false,
        $condition = 'AND'
    ) {
        return $this->_add($field, $value, $condition, '>');
    }

    /**
     * @param string $field
     * @param bool $value
     * @return Where
     */
    public function orSuperior(
        $field,
        $value = false
    ) {
        return $this->superior($field, $value, 'OR');
    }

    /**
     * @param string $field
     * @param bool $value
     * @param string $condition
     * @return Where
     */
    public function inferior(
        $field,
        $value = false,
        $condition = 'AND'
    ) {
        return $this->_add($field, $value, $condition, '<');
    }

    /**
     * @param string $field
     * @param bool $value
     * @return Where
     */
    public function orInferior(
        $field,
        $value = false
    ) {
        return $this->inferior($field, $value, 'OR');
    }

    /**
     * @param string $field
     * @param string $condition
     * @return Where
     */
    public function null(
        $field,
        $condition = 'AND'
    ) {
        return $this->_add($field, null, $condition, 'IS NULL');
    }

    /**
     * @param string $field
     * @return Where
     */
    public function orNull(
        $field
    ) {
        return $this->null($field, 'OR');
    }

    /**
     * @param string $field
     * @param string $condition
     * @return Where
     */
    public function notNull(
        $field,
        $condition = 'AND'
    ) {
        return $this->_add($field, null, $condition, 'IS NOT NULL');
    }

    /**
     * @param string $field
     * @return Where
     */
    public function orNotNull(
        $field
    ) {
        return $this->notNull($field, 'OR');
    }

    /**
     * @param string $field
     * @param array $value
     * @param string $condition
     * @return Where
     */
    public function in(
        $field,
        array $value = array(),
        $condition = 'AND'
    ) {
        return $this->_add($field, $value, $condition, 'IN');
    }

    /**
     * @param string $field
     * @param array $value
     * @return Where
     */
    public function orIn(
        $field,
        array $value = array()
    ) {
        return $this->in($field, $value, 'OR');
    }

    /**
     * @param string $field
     * @param array $value
     * @param string $condition
     * @return Where
     */
    public function notIn(
        $field,
        array $value = array(),
        $condition = 'AND'
    ) {
        return $this->_add($field, $value, $condition, 'NOT IN');
    }

    /**
     * @param string $field
     * @param array $value
     * @return Where
     */
    public function orNotIn(
        $field,
        array $value = array()
    ) {
        return $this->notIn($field, $value, 'OR');
    }

    /**
     * @param string $field
     * @param bool $value
     * @param string $condition
     * @return Where
     */
    public function equal(
        $field,
        $value = false,
        $condition = 'AND'
    ) {
        return $this->_add($field, $value, $condition, '=');
    }

    /**
     * @param string $field
     * @param bool $value
     * @return Where
     */
    public function orEqual(
        $field,
        $value = false
    ) {
        return $this->equal($field, $value, 'OR');
    }

    /**
     * @param string $field
     * @param bool $value
     * @param string $condition
     * @return Where
     */
    public function notEqual(
        $field,
        $value = false,
        $condition = 'AND'
    ) {
        return $this->_add($field, $value, $condition, '!=');
    }

    /**
     * @param string $field
     * @param bool $value
     * @return Where
     */
    public function orNotEqual(
        $field,
        $value = false
    ) {
        return $this->notEqual($field, $value, 'OR');
    }

    /**
     * @param string $field
     * @param bool $value
     * @param string $condition
     * @return Where
     */
    public function like(
        $field,
        $value = false,
        $condition = 'AND'
    ) {
        return $this->_add($field, $value, $condition, 'LIKE');
    }

    /**
     * @param string $field
     * @param bool $value
     * @return Where
     */
    public function orLike(
        $field,
        $value = false
    ) {
        return $this->like($field, $value, 'OR');
    }

    /**
     * @param string $field
     * @param bool $value
     * @param string $condition
     * @return Where
     */
    public function notLike(
        $field,
        $value = false,
        $condition = 'AND'
    ) {
        return $this->_add($field, $value, $condition, 'NOT LIKE');
    }

    /**
     * @param string $field
     * @param bool $value
     * @return Where
     */
    public function orNotLike(
        $field,
        $value = false
    ) {
        return $this->notLike($field, $value, 'OR');
    }

    /**
     * @param string $field
     * @param bool $value
     * @param string $condition
     * @param string $operator
     * @return $this
     */
    protected function _add(
        $field,
        $value = false,
        $condition = 'AND',
        $operator = '='
    ) {
        if (strlen($this->where)) {
            $this->where = ' ' . $condition . ' ';
        }
        $this->where .= $this->_buildWhereElement($field, $value, $condition, $operator);
        return $this;
    }

    /**
     * @param string $fieldName
     * @param mixed  $value
     * @param string $condition
     * @param string $operator
     * @return string
     */
    protected function _buildWhereElement(
        $fieldName,
        $value,
        $condition,
        $operator
    ) {
        $element   = $fieldName . ' ' . $operator . ' ';
        $fieldType = $this->query->tableDefinition->getTypeOf($fieldName);
        switch ($operator) {
            case 'IN'     :
            case 'NOT IN' :
                $element .= implode(',', $this->query->pdo->quoteArray($value, $fieldType));
                break;
            default:
                if (is_array($value)) {
                    $element .= implode(
                        ' ' . $condition . ' ' . $element,
                        $this->query->pdo->quoteArray($value, $fieldType)
                    );
                }
                else if (false === $value) {
                    $element .= ':'.$fieldName;
                }
                else if (null !== $value) {
                    $element .= $this->query->pdo->quote($value, $fieldType);
                }

                break;
        }
        return $element;
    }
}