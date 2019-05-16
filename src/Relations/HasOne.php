<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;
use Nip\Records\AbstractModels\Record as Record;
use Nip\Records\Collections\Collection as RecordCollection;

/**
 * Class HasOneOrMany
 * @package Nip\Records\Relations
 */
class HasOne extends Relation
{
    /**
     * @var string
     */
    protected $type = 'hasOne';

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return string
     */
    public function generateFK()
    {
        return $this->getManager()->getPrimaryFK();
    }

    /**
     * @inheritDoc
     */
    public function initResults()
    {
        $withManager = $this->getWith();
        $foreignKey = $this->getItem()->{$this->getPrimaryKey()};
        $results = $withManager->findByField($this->getFK(), $foreignKey);
        if (count($results) > 0) {
            $this->setResults($results->rewind());
            return;
        }

        return $this->setResults(false);
    }

    /**
     * @param $dictionary
     * @param $collection
     * @param $record
     * @return mixed
     */
    public function getResultsFromCollectionDictionary($dictionary, $collection, $record)
    {
        $primaryKey = $record->{$this->getPrimaryKey()};
        if (isset($dictionary[$primaryKey])) {
            return $dictionary[$primaryKey];
        }

        return null;
    }

    /**
     * Build model dictionary keyed by the relation's foreign key.
     *
     * @param RecordCollection $collection
     * @return array
     */
    protected function buildDictionary(RecordCollection $collection)
    {
        if ($collection->isEmpty()) {
            return [];
        }
        $dictionary = [];
        foreach ($collection as $record) {
            $dictionary[$this->getDictionaryKey($record)] = $record;
        }

        return $dictionary;
    }

    /**
     * @param Record $record
     * @return array
     * @throws \Exception
     */
    protected function getDictionaryKey(Record $record)
    {
        $withPK = $this->getPrimaryKey();

        return $record->{$withPK};
    }

    /**
     * @inheritdoc
     */
    public function populateQuerySpecific(AbstractQuery $query)
    {
    }
}
