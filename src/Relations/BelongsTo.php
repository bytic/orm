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

    /**
     * @var string
     */
    protected $type = 'belongsTo';

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return string
     */
    public function generateFK()
    {
        return $this->getWith()->getPrimaryFK();
    }

    public function initResults()
    {
        $manager = $this->getWith();
        $foreignKey = $this->getItem()->{$this->getFK()};
        $this->setResults($manager->findOne($foreignKey));
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
