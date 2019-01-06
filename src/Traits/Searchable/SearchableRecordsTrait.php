<?php

namespace Nip\Records\Traits\Searchable;

use Nip\Database\Query\AbstractQuery as Query;
use Nip\Records\Navigator\Pagination\Paginator;
use Nip\Records\AbstractModels\Record;
use Nip\Records\Collections\Collection as RecordCollection;

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
