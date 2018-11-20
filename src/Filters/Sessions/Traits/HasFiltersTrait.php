<?php

namespace Nip\Records\Filters\Sessions\Traits;

use Nip\Records\Filters\AbstractFilter;
use Nip\Records\Filters\Column\AbstractFilter as AbstractColumnFilter;

/**
 * Trait HasFiltersTrait
 * @package Nip\Records\Filters\Traits
 */
trait HasFiltersTrait
{
    /**
     * @var AbstractFilter[]|AbstractColumnFilter[]
     */
    protected $filters = [];

    /**
     * @return AbstractFilter[]|AbstractColumnFilter[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param AbstractFilter[]|AbstractColumnFilter[] $filters
     */
    public function setFilters($filters)
    {
        foreach ($filters as $filter) {
            $filter->setSession($this);
        }
        $this->filters = $filters;
    }

    public function resetFilters()
    {
        $filters = $this->getFilters();
        foreach ($filters as $filter) {
            $filter->setValue(null);
        }
    }
}
