<?php

namespace Lia\Database\PdoBundle\Query\Clause;


interface WhereInterface
{
    /**
     * @param string $where
     * @return $this
     */
    public function addInline($where);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function superior($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orSuperior($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function inferior($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orInferior($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function null($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orNull($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function notNull($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orNotNull($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function in($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orIn($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function notIn($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orNotIn($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function equal($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orEqual($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function notEqual($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orNotEqual($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function like($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orLike($field);

    /**
     * @param string $field
     * @param string $mappedKey
     * @param string $condition
     * @return $this
     */
    public function notLike($field, $mappedKey=null, $condition = 'AND');

    /**
     * @param string $field
     * @return $this
     */
    public function orNotLike($field);
}