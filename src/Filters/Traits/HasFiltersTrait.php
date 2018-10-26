<?php

namespace Nip\Records\Filters\Traits;

use Nip\Records\Filters\AbstractFilter;
use Nip\Records\Filters\Column\AbstractFilter as AbstractColumnFilter;
use Nip\Records\Filters\FilterManager;

/**
 * Trait HasFiltersTrait
 * @package Nip\Records\Filters\Traits
 */
trait HasFiltersTrait
{
    /**
     * @var AbstractFilter[]|AbstractColumnFilter[]
     */
    protected $items = [];

    /**
     * @param mixed $type
     * @return AbstractFilter|AbstractColumnFilter ;
     */
    public function newFilter($type)
    {
        $class = $this->getFilterClass($type);
        $filter = new $class;

        return $filter;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getFilterClass($type)
    {
        return '\Nip\Records\Filters\\' . $type;
    }

    /**
     * @param AbstractFilter|AbstractColumnFilter $filter
     */
    public function addFilter($filter)
    {
        $this->prepareFilter($filter);
        $this->set($filter->getName(), $filter);
    }

    /**
     * @param AbstractFilter|AbstractColumnFilter $filter
     */
    public function prepareFilter($filter)
    {
        $filter->setManager($this);
    }

    /**
     * @return AbstractFilter[]|AbstractColumnFilter[]
     */
    public function getFilters()
    {
        return $this->all();
    }

    /**
     * @return AbstractFilter[]|AbstractColumnFilter[]
     */
    public function getClonedFilters()
    {
        $return = [];
        $filters = $this->getFilters();
        foreach ($filters as $key => $filter) {
            $return[$key] = clone $filter;
        }
        return $return;
    }
}