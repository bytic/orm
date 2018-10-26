<?php

namespace Nip\Records\Filters\Sessions\Traits;

use Nip\Database\Query\Select as SelectQuery;

/**
 * Trait HasQueryTrait
 * @package Nip\Records\Filters\Sessions\Traits
 */
trait HasQueryTrait
{
    /**
     * @param SelectQuery $query
     * @return SelectQuery
     */
    public function filterQuery($query)
    {
        $filters = $this->getFilters();
        foreach ($filters as $filter) {
            if ($filter->isActive()) {
                $filter->filterQuery($query);
            }
        }

        return $query;
    }
}
