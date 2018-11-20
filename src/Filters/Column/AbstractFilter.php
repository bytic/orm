<?php

namespace Nip\Records\Filters\Column;

use Nip\Records\Filters\Column\Traits\HasDbNameTrait;

/**
 * Class AbstractFilter
 * @package Nip\Records\Filters\Column
 */
abstract class AbstractFilter extends \Nip\Records\Filters\AbstractFilter implements FilterInterface
{
    use HasDbNameTrait;

    protected $field;

    public function initName()
    {
        $this->setName($this->getField());
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     * @return self
     */
    public function setField($field)
    {
        $this->field = $field;

        return $this;
    }

    public function initRequestField()
    {
        $this->setRequestField($this->getField());
    }
}
