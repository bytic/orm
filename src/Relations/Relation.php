<?php

namespace Nip\Records\Relations;

use Exception;
use Nip\Database\Connections\Connection;
use Nip\Database\Query\AbstractQuery;
use Nip\Database\Query\Select as Query;
use Nip\HelperBroker;
use Nip\Records\AbstractModels\Record;
use Nip\Records\Collections\Collection;
use Nip\Records\Collections\Collection as RecordCollection;
use Nip\Records\Locator\ModelLocator;
use Nip\Records\Relations\Exceptions\RelationsNeedsAName;
use Nip_Helper_Arrays as ArraysHelper;

/**
 * Class Relation
 * @package Nip\Records\Relations
 */
abstract class Relation
{
    use Traits\HasManagerTrait;
    use Traits\HasCollectionResults;
    use Traits\HasWithTrait;
    use Traits\HasForeignKeyTrait;
    use Traits\HasPrimaryKeyTrait;
    use Traits\HasItemTrait;
    use Traits\HasParamsTrait;

    /**
     * @var
     */
    protected $name = null;

    /**
     * @var string
     */
    protected $type = 'relation';

    /**
     * @var null|string
     */
    protected $table = null;

    /**
     * @var Query
     */
    protected $query;

    /**
     * @var bool
     */
    protected $populated = false;

    /**
     * @var null|Collection|Record
     */
    protected $results = null;

    /**
     * @return Query
     * @throws Exception
     */
    public function getQuery()
    {
        if ($this->query == null) {
            $this->initQuery();
        }

        return $this->query;
    }

    /**
     * @param $query
     * @return static
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function initQuery()
    {
        $query = $this->newQuery();
        $this->populateQuerySpecific($query);

        $this->query = $query;
    }

    /**
     * @return Query
     */
    public function newQuery()
    {
        return $this->getWith()->paramsToQuery();
    }

    /**
     * @return mixed
     * @throws RelationsNeedsAName
     */
    public function getName()
    {
        if ($this->name === null) {
            throw new RelationsNeedsAName();
        }
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $name
     * @return \Nip\Records\AbstractModels\RecordManager
     */
    public function getModelManagerInstance($name)
    {
        return ModelLocator::get($name);
    }


    /**
     * @param AbstractQuery $query
     */
    abstract public function populateQuerySpecific(AbstractQuery $query);

    /**
     * @return \Nip\Database\Query\Delete
     * @throws Exception
     */
    public function getDeleteQuery()
    {
        $query = $this->getWith()->newDeleteQuery();
        $this->populateQuerySpecific($query);

        return $query;
    }

    /**
     * @return Connection
     */
    public function getDB()
    {
        return $this->getManager()->getDB();
    }


    /**
     * @return string
     */
    public function getTable()
    {
        if ($this->table == null) {
            $this->initTable();
        }

        return $this->table;
    }

    /**
     * @param $name
     */
    public function setTable($name)
    {
        $this->table = $name;
    }

    protected function initTable()
    {
        $this->setTable($this->generateTable());
    }

    /**
     * @return string
     */
    protected function generateTable()
    {
        return $this->getWith()->getTable();
    }

    /**
     * Get the results of the relationship.
     * @return Record|RecordCollection
     */
    public function getResults()
    {
        if (!$this->isPopulated()) {
            $this->initResults();
        }

        return $this->results;
    }

    /**
     * @param $results
     * @return null
     */
    public function setResults($results)
    {
        $this->results = $results;
        $this->populated = true;

        return $this->results;
    }

    /**
     * @return bool
     */
    public function isPopulatable()
    {
        return !empty($this->getItemRelationPrimaryKey());
    }

    /**
     * @return bool
     */
    public function isPopulated()
    {
        return $this->populated == true;
    }

    abstract public function initResults();

    /**
     * @param RecordCollection $collection
     * @return RecordCollection
     * @throws Exception
     */
    public function getEagerResults($collection)
    {
        if ($collection->count() < 1) {
            return $this->newCollection();
        }
        $query = $this->getEagerQuery($collection);

        return $this->getWith()->findByQuery($query);
    }

    /**
     * @param RecordCollection $collection
     * @return Query
     */
    public function getEagerQuery(RecordCollection $collection)
    {
        $fkList = $this->getEagerFkList($collection);
        $query = $this->populateEagerQueryFromFkList($this->newQuery(), $fkList);
        return $query;
    }

    /**
     * @param Query $query
     * @param array $fkList
     * @return Query
     */
    protected function populateEagerQueryFromFkList($query, $fkList)
    {
        $query->where($this->getWithPK() . ' IN ?', $fkList);

        return $query;
    }

    /**
     * @param RecordCollection $collection
     * @return array
     */
    public function getEagerFkList(RecordCollection $collection)
    {
        /** @var ArraysHelper $arrayHelper */
        $arrayHelper = HelperBroker::get('Arrays');
        $return = $arrayHelper->pluck($collection, $this->getFK());

        return array_unique($return);
    }

    /**
     * @param RecordCollection $collection
     * @param RecordCollection $recordsLoaded
     *
     * @return RecordCollection
     */
    public function match(RecordCollection $collection, RecordCollection $recordsLoaded)
    {
        $dictionary = $this->buildDictionary($recordsLoaded);

        foreach ($collection as $record) {
            /** @var Record $record */
            $results = $this->getResultsFromCollectionDictionary($dictionary, $collection, $record);
            /** @noinspection PhpUnhandledExceptionInspection */
            $record->getRelation($this->getName())->setResults($results);
        }

        return $recordsLoaded;
    }

    /**
     * Build model dictionary keyed by the relation's foreign key.
     *
     * @param RecordCollection $collection
     * @return array
     */
    abstract protected function buildDictionary(RecordCollection $collection);

    /**
     * @param $dictionary
     * @param Collection $collection
     * @param Record $record
     * @return mixed
     */
    abstract public function getResultsFromCollectionDictionary($dictionary, $collection, $record);

    public function save()
    {
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /** @noinspection PhpDocMissingThrowsInspection
     * @return string
     */
    protected function debugString()
    {
        return 'Relation'
            . ' Manager:[' . ($this->hasManager() ? $this->getManager()->getClassName() : '') . ']'
            . ' name:[' . $this->getName() . '] '
            . ' params:[' . serialize($this->getParams()) . ']';
    }
}
