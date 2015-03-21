<?php

namespace Lia\Database\PdoBundle\Query\Clause;


interface WhereInterface
{
    /**
     * @param string $where
     * @param array $params
     * @return $this
     */
    //public function add($where, array $params = null);

    /**
     * @param string $field
     * @param string|bool $value
     * @param string $condition
     * @return $this
     */
    public function superior($field, $value = false, $condition = 'AND');

    /**
     * @param string $field
     * @param string|bool $value
     * @return $this
     */
    public function orSuperior($field, $value = false);

    /**
     * @param string $field
     * @param string|bool $value
     * @param string $condition
     * @return $this
     */
    public function inferior($field, $value = false, $condition = 'AND');

    /**
     * @param string $field
     * @param string|bool $value
     * @return $this
     */
    public function orInferior($field, $value = false);

    /**
     * @param string $field
     * @param string $condition
     * @return $this
     */
    public function null($field, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orNull($field);

    /**
     * @param string $field
     * @param string $condition
     * @return $this
     */
    public function notNull($field, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orNotNull($field);

    /**
     * @param string $field
     * @param array $value
     * @param string $condition
     * @return $this
     */
    public function in($field, array $value = array(), $condition = 'AND');

    /**
     * @param string $field
     * @param array $value
     * @return $this
     */
    public function orIn($field, array $value = array());

    /**
     * @param string $field
     * @param array $value
     * @param string $condition
     * @return $this
     */
    public function notIn($field, array $value = array(), $condition = 'AND');

    /**
     * @param string $field
     * @param array $value
     * @return $this
     */
    public function orNotIn($field, array $value = array());

    /**
     * @param string $field
     * @param string|bool $value
     * @param string $condition
     * @return $this
     */
    public function equal($field, $value = false, $condition = 'AND');

    /**
     * @param string $field
     * @param string|bool $value
     * @return $this
     */
    public function orEqual($field, $value = false);

    /**
     * @param string $field
     * @param string|bool $value
     * @param string $condition
     * @return $this
     */
    public function notEqual($field, $value = false, $condition = 'AND');

    /**
     * @param string $field
     * @param string|bool $value
     * @return $this
     */
    public function orNotEqual($field, $value = false);

    /**
     * @param string $field
     * @param string|bool $value
     * @param string $condition
     * @return $this
     */
    public function like($field, $value = false, $condition = 'AND');

    /**
     * @param string $field
     * @param string|bool $value
     * @return $this
     */
    public function orLike($field,$value = false);

    /**
     * @param string $field
     * @param string|bool $value
     * @param string $condition
     * @return $this
     */
    public function notLike($field, $value = false, $condition = 'AND');

    /**
     * @param string $field
     * @param string|bool $value
     * @return $this
     */
    public function orNotLike($field, $value = false);
}