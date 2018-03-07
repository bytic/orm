<?php

namespace Nip\Records\Navigator\Pagination;

use Nip\Database\Query\Select as SelectQuery;

/**
 * Class AbstractPaginator
 * @package Nip\Records\Navigator\Pagination
 */
abstract class AbstractPaginator
{

    /**
     * @var SelectQuery
     */
    protected $query = null;

    /**
     * @var int
     */
    protected $page = 1;

    /**
     * @var int
     */
    protected $itemsPerPage = 20;

    /**
     * @var null|int
     */
    protected $count = null;

    /**
     * @var int
     */
    protected $pages;

    /**
     * @param SelectQuery $query
     * @return SelectQuery
     */
    public function paginate($query)
    {
        $query->limit($this->getLimitStart(), $this->getItemsPerPage());

        $this->setQuery($query);

        return $query;
    }

    /**
     * @return SelectQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param SelectQuery $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @deprecated Method is called automatically on getCount
     */
    public function count()
    {
        $this->getCount();
    }

    /**
     * Does the count for all records
     */
    protected function doCount()
    {
        $query = $this->getCountQuery();
        $result = $query->execute()->fetchResult();

        $this->count = intval($result['count']);
        $this->pages = intval($this->count / $this->itemsPerPage);

        if ($this->count % $this->itemsPerPage != 0) {
            $this->pages++;
        }

        if ($this->pages == 0) {
            $this->pages = 1;
        }
    }

    /**
     * @return SelectQuery
     */
    protected function getCountQuery()
    {
        $query = clone $this->getQuery();
        $query->setCols();
        $query->count('*', 'count');
        $query->limit(1);
        return $query;
    }

    /**
     * @param bool $page
     * @return $this
     */
    public function setPage($page = false)
    {
        if ($page) {
            $this->page = $page;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * @param $items
     * @return $this
     */
    public function setItemsPerPage($items)
    {
        if ($items > 0) {
            $this->itemsPerPage = $items;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @return float|int
     */
    public function getLimitStart()
    {
        return ($this->getPage() - 1) * $this->getItemsPerPage();
    }

    /**
     * @return int
     */
    public function getCount()
    {
        if ($this->count === null) {
            $this->doCount();
        }
        return $this->count;
    }
}
