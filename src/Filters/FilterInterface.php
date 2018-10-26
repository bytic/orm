<?php

namespace Nip\Records\Filters;

/**
 * Interface FilterInterface
 * @package Nip\Records\Filters
 */
interface FilterInterface
{
    public function getName();

    public function getValue();

    public function getManager();

    /**
     * @param $field
     * @return mixed
     */
    public function setManager($field);
}
