<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;
use Nip\Records\AbstractModels\Record;
use Nip\Records\Collections\Collection as RecordCollection;

/**
 * Class BelongsTo
 * @package Nip\Records\Relations
 */
class BelongsTo extends Relation
{
    public const NAME = 'belongsTo';

    /**
     * @var string
     */
    protected $type = self::NAME;

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return string
     */
    public function generateFK()
    {
        return $this->getWith()->getPrimaryFK();
    }

    /**
     * @inheritDoc
     */
    public function initResults()
    {
        $withManager = $this->getWith();
        $foreignKey = $this->getItem()->{$this->getFK()};
        if (empty($foreignKey)) {
            return $this->setResults(false);
        }
        $results = $withManager->findByField($this->getWithPK(), $foreignKey);
        if (count($results) < 1) {
            return $this->setResults(false);
        }

        $this->setResults($results->rewind());
        return;
    }

    /**
     * @param $dictionary
     * @param $collection
     * @param $record
     * @return mixed
     */
    public function getResultsFromCollectionDictionary($dictionary, $collection, $record)
    {
        $primaryKey = $record->{$this->getFK()};
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
        $withPK = $this->getWithPK();

        return $record->{$withPK};
    }

    /**
     * @inheritdoc
     */
    public function populateQuerySpecific(AbstractQuery $query)
    {
    }
}
