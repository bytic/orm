<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;
use Nip\Records\Collections\Collection as RecordCollection;

/**
 * Class HasOneOrMany
 * @package Nip\Records\Relations
 */
class HasOne extends HasOneOrMany
{
    public const NAME = 'hasOne';

    /**
     * @var string
     */
    protected $type = self::NAME;

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
        $primaryKey = $this->getDictionaryKey();
        foreach ($collection as $record) {
            $dictionary[$record->{$primaryKey}] = $record;
        }

        return $dictionary;
    }

    /**
     * @inheritdoc
     */
    public function populateQuerySpecific(AbstractQuery $query)
    {
    }

    /**
     * @inheritDoc
     */
    public function save()
    {
        $result = $this->getResults();
        if (!is_object($result)) {
            return;
        }
        $primaryKey = $this->getPrimaryKey();
        $foreignKey = $this->getFK();

        $result->{$foreignKey} = $this->getItem()->{$primaryKey};
        $result->saveRecord();
        return true;
    }
}
