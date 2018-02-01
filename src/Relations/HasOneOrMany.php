<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\Select as Query;
use Nip\HelperBroker;
use Nip\Records\AbstractModels\Record as Record;
use Nip\Records\Collections\Associated as AssociatedCollection;
use Nip\Records\Collections\Collection;
use Nip\Records\Collections\Collection as RecordCollection;
use Nip\Records\Relations\Traits\HasCollectionResults;

/**
 * Class HasOneOrMany
 * @package Nip\Records\Relations
 */
abstract class HasOneOrMany extends Relation
{
    use HasCollectionResults;

    /**
     * @var string
     */
    protected $type = 'hasMany';

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->hasResults()) {
            $collection = $this->getResults();
            foreach ($collection as $item) {
                $this->saveResult($item);
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    public function hasResults()
    {
        return $this->isPopulated() && count($this->getResults()) > 0;
    }

    /**
     * @param Record $item
     */
    public function saveResult(Record $item)
    {
        $pk = $this->getManager()->getPrimaryKey();
        $fk = $this->getFK();
        $item->{$fk} = $this->getItem()->{$pk};
        $item->saveRecord();
    }

    public function initResults()
    {
        $query = $this->getQuery();
        $items = $this->getWith()->findByQuery($query);
        $collection = $this->newCollection();
        $this->populateCollection($collection, $items);
        $this->setResults($collection);
    }

    /**
     * @param RecordCollection $collection
     * @param Collection $items
     */
    public function populateCollection(RecordCollection $collection, $items)
    {
        foreach ($items as $item) {
            $collection->add($item);
        }
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @param RecordCollection $collection
     * @return Query
     */
    public function getEagerQuery(RecordCollection $collection)
    {
        $fkList = $this->getEagerFkList($collection);
        $query = $this->newQuery();
        $query->where($this->getFK() . ' IN ?', $fkList);
        return $query;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @param RecordCollection $collection
     * @return array
     */
    public function getEagerFkList(RecordCollection $collection)
    {
        $key = $collection->getManager()->getPrimaryKey();
        $return = HelperBroker::get('Arrays')->pluck($collection, $key);

        return array_unique($return);
    }

    /**
     * @param array $dictionary
     * @param Collection $collection
     * @param Record $record
     * @return AssociatedCollection
     */
    public function getResultsFromCollectionDictionary($dictionary, $collection, $record)
    {
        $fk = $record->getManager()->getPrimaryKey();
        $pk = $record->{$fk};
        $collection = $this->newCollection();

        if ($dictionary[$pk]) {
            foreach ($dictionary[$pk] as $record) {
                $collection->add($record);
            }
        }
        return $collection;
    }

    /**
     * Build model dictionary keyed by the relation's foreign key.
     *
     * @param RecordCollection $collection
     * @return array
     */
    protected function buildDictionary(RecordCollection $collection)
    {
        $dictionary = [];
        $pk = $this->getDictionaryKey();
        foreach ($collection as $record) {
            $dictionary[$record->{$pk}][] = $record;
        }
        return $dictionary;
    }

    /**
     * @return string
     */
    protected function getDictionaryKey()
    {
        return $this->getFK();
    }
}
