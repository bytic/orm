<?php

namespace Nip\Records\Traits\Searchable;

use Nip\Database\Query\AbstractQuery as Query;
use Nip\Records\AbstractModels\Record;
use Nip\Records\Collections\Collection as RecordCollection;
use Nip\Records\Navigator\Pagination\Paginator;

/**
 * Trait SearchableRecordsTrait
 * @package Nip\Records\Traits\Searchable
 */
trait SearchableRecordsTrait
{
    /**
     * Returns paginated results
     * @param Paginator $paginator
     * @param array $params
     * @return mixed
     */
    public function paginate(Paginator $paginator, $params = [])
    {
        $query = $this->paramsToQuery($params);

        $countQuery = $this->getDB()->newSelect();
        $countQuery->count(['*', 'count']);
        $countQuery->from([$query, 'tbl']);
        $results = $countQuery->execute()->fetchResults();
        $count = $results[0]['count'];

        $paginator->setCount($count);

        $params['limit'] = $paginator->getLimits();

        return $this->findByParams($params);
    }

    /**
     * @param array $params
     */
    protected function injectParams(&$params = [])
    {
    }

    /**
     * @return RecordCollection
     */
    public function findAll()
    {
        return $this->findByParams();
    }

    /**
     * @param int $count
     * @return RecordCollection
     */
    public function findLast($count = 9)
    {
        return $this->findByParams([
            'limit' => $count,
        ]);
    }

    /**
     * Checks the registry before fetching from the database
     * @param mixed $primary
     * @return Record
     */
    public function findOne($primary)
    {
        $item = $this->getRegistry()->get($primary);
        if (!$item) {
            $all = $this->getRegistry()->get("all");
            if ($all) {
                $item = $all[$primary];
            }
            if (!$item) {
                $params['where'][] = ["`{$this->getTable()}`.`{$this->getPrimaryKey()}` = ?", $primary];
                $item = $this->findOneByParams($params);
                if ($item) {
                    $this->getRegistry()->set($primary, $item);
                }

                return $item;
            }
        }

        return $item;
    }

    /**
     * When searching by primary key, look for items in current registry before
     * fetching them from the database
     *
     * @param array $pk_list
     * @return RecordCollection
     */
    public function findByPrimary($pk_list = [])
    {
        $pk = $this->getPrimaryKey();
        $return = $this->newCollection();

        if ($pk_list) {
            $pk_list = array_unique($pk_list);
            foreach ($pk_list as $key => $value) {
                $item = $this->getRegistry()->get($value);
                if ($item) {
                    unset($pk_list[$key]);
                    $return[$item->{$pk}] = $item;
                }
            }
            if ($pk_list) {
                $query = $this->paramsToQuery();
                $query->where("$pk IN ?", $pk_list);
                $items = $this->findByQuery($query);

                if (count($items)) {
                    foreach ($items as $item) {
                        $this->getRegistry()->set($item->{$pk}, $item);
                        $return[$item->{$pk}] = $item;
                    }
                }
            }
        }

        return $return;
    }


    /**
     * Finds one Record using params array
     *
     * @param array $params
     * @return Record|null
     */
    public function findOneByParams(array $params = [])
    {
        $params['limit'] = 1;
        $records = $this->findByParams($params);
        if (count($records) > 0) {
            return $records->rewind();
        }

        return null;
    }


    /**
     * Finds one Record by field
     *
     * @param $field
     * @param $value
     * @return Record|null
     */
    public function findOneByField($field, $value)
    {
        $params['where'][] = ["$field " . (is_array($value) ? "IN" : "=") . " ?", $value];

        return $this->findOneByParams($params);
    }

    /**
     * Finds Records using params array
     *
     * @param array $params
     * @return RecordCollection
     */
    public function findByParams($params = [])
    {
        $query = $this->paramsToQuery($params);

        return $this->findByQuery($query, $params);
    }

    /**
     * @param Query $query
     * @param array $params
     * @return bool
     */
    public function findOneByQuery($query, $params = [])
    {
        $query->limit(1);
        $return = $this->findByQuery($query, $params);
        if (count($return) > 0) {
            return $return->rewind();
        }

        return null;
    }

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function findByField($field, $value)
    {
        $params['where'][] = ["$field " . (is_array($value) ? "IN" : "=") . " ?", $value];

        return $this->findByParams($params);
    }

    /**
     * @param Query $query
     * @param array $params
     * @return RecordCollection
     */
    public function findByQuery($query, $params = [])
    {
        $return = $this->newCollection();

        $results = $this->getDB()->execute($query);
        if ($results->numRows() > 0) {
            $pk = $this->getPrimaryKey();
            /** @noinspection PhpAssignmentInConditionInspection */
            while ($row = $results->fetchResult()) {
                $item = $this->getNew($row);
                if (is_string($pk)) {
                    $this->getRegistry()->set($item->getPrimaryKey(), $item);
                }
                if (isset($params['indexKey']) && !empty($params['indexKey'])) {
                    $return->add($item, $params['indexKey']);
                } else {
                    $return->add($item);
                }
            }
        }

        return $return;
    }
}
