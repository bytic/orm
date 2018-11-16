<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\Select as Query;
use Nip\HelperBroker;
use Nip_Helper_Arrays as ArraysHelper;
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
        $primaryKey = $this->getPrimaryKey();
        $foreignKey = $this->getFK();

        $item->{$foreignKey} = $this->getItem()->{$primaryKey};
        $item->saveRecord();
    }

    /**
     * @throws \Exception
     */
    public function initResults()
    {
        $collection = $this->newCollection();
        if ($this->isPopulatable()) {
            $query = $this->getQuery();
            $items = $this->getWith()->findByQuery($query);
            $this->populateCollection($collection, $items);
        }
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
     * @inheritdoc
     */
    public function populateEagerQueryFromFkList($query, $fkList)
    {
        $query->where($this->getFK() . ' IN ?', $fkList);
        return $query;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @param RecordCollection $collection
     * @return array
     */
    public function getEagerFkList(RecordCollection $collection)
    {
        if ($collection->isEmpty()) {
            return [];
        }
        $key = $this->getPrimaryKey();
        /** @var ArraysHelper $arrayHelper */
        $arrayHelper = HelperBroker::get('Arrays');
        $return = $arrayHelper->pluck($collection, $key);

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
        /** Use Relation Primary Key in case it is overwritten */
        $foreignKey = $this->getPrimaryKey();
        $primaryKey = $record->{$foreignKey};
        $collection = $this->newCollection();

        if (isset($dictionary[$primaryKey])) {
            foreach ($dictionary[$primaryKey] as $record) {
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
        $primaryKey = $this->getDictionaryKey();
        foreach ($collection as $record) {
            $dictionary[$record->{$primaryKey}][] = $record;
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
