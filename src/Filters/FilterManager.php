<?php

namespace Nip\Records\Filters;

use Nip\Collections\AbstractCollection;
use Nip\Collections\Traits\ArrayAccessTrait;
use Nip\Database\Query\Select as SelectQuery;
use Nip\Records\Filters\Column\AbstractFilter as AbstractColumnFilter;
use Nip\Records\Filters\Traits\HasFiltersTrait;
use Nip\Records\Filters\Traits\HasRecordManagerTrait;
use Nip\Records\Filters\Traits\HasSessionsTrait;
use Nip\Utility\Traits\HasRequestTrait;

/**
 * Class FilterManager
 * @package Nip\Records\Filters
 *
 * @method AbstractFilter[]|AbstractColumnFilter[] all()
 * @method AbstractFilter|AbstractColumnFilter get($name)
 */
class FilterManager extends AbstractCollection
{
    use HasRequestTrait;
    use ArrayAccessTrait;
    use HasFiltersTrait;
    use HasSessionsTrait;
    use HasRecordManagerTrait;

    const DEFAULT_SESSION = 'default';

    /**
     * Init filter Manager, init default filters
     */
    public function init()
    {
    }

    /** @noinspection PhpDocMissingThrowsInspection
     * @param null $session
     * @return null
     */
    public function getFiltersArray($session = null)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $session = $this->getSession($session);
        return $session->getFiltersArray();
    }

    /** @noinspection PhpDocMissingThrowsInspection
     * @param SelectQuery $query
     * @param null $session
     * @return SelectQuery
     */
    public function filterQuery($query, $session = null)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $session = $this->getSession($session);
        return $session->filterQuery($query);
    }
}
