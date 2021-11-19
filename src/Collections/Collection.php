<?php

namespace Nip\Records\Collections;

use ByTIC\ORM\Exception\ORMException;
use Nip\Collections\Collection as AbstractCollection;
use Nip\HelperBroker;
use Nip\Records\AbstractModels\Record as Record;
use Nip\Records\AbstractModels\RecordManager as Records;
use Nip\Records\Relations\Relation;
use Nip\Records\Relations\Traits\HasCollectionResults;

/**
 * Class Collection
 * @package Nip\Records\Collections
 */
class Collection extends AbstractCollection
{
    protected $_indexKey = false;

    /**
     * @var Records
     */
    protected $_manager = null;


    /**
     * @param HasCollectionResults|Relation $relation
     */
    public function initFromRelation($relation)
    {
        $manager = $relation->getWith();
        if ($manager instanceof Records) {
            $this->setManager($manager);
        }

        $indexKey = $relation->getParam('indexKey');
        if ($indexKey) {
            $this->setIndexKey($indexKey);
        }
    }


    /**
     * @param $relations
     */
    public function loadRelations($relations)
    {
        if (count($this) < 1) {
            return;
        }

        if (is_string($relations)) {
            $relations = func_get_args();
        }

        foreach ($relations as $relation) {
            $this->loadRelation($relation);
        }
    }

    /**
     * @param $name
     * @return Collection
     */
    public function loadRelation($name): Collection
    {
        $relation = $this->getRelation($name);
        if (!($relation instanceof Relation)) {
            throw new ORMException("Invalid relation {$name} on collection of {$this->getManager()->getClassName()}");
        }
        if (count($this) < 1) {
            return $relation->newCollection();
        }

        $results = $relation->getEagerResults($this);
        $relation->match($this, $results);
        return $results;
    }

    /**
     * @param $name
     * @return \Nip\Records\Relations\Relation|null
     */
    public function getRelation($name)
    {
        return $this->getManager()->getRelation($name);
    }

    /**
     * @return Records
     */
    public function getManager()
    {
        if ($this->_manager == null) {
            $this->initManager();
        }

        return $this->_manager;
    }

    /**
     * @param Records $manager
     * @return $this
     */
    public function setManager(Records $manager)
    {
        $this->_manager = $manager;

        return $this;
    }

    public function initManager()
    {
        $manager = $this->rewind()->getManager();
        $this->setManager($manager);
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        $return = [];
        foreach ($this as $item) {
            $return = $item->toArray();
        }

        return json_encode($return);
    }

    public function save()
    {
        if (count($this) > 0) {
            foreach ($this as $item) {
                $item->save();
            }
        }
    }

    /**
     * @param Record $record
     * @param string $index
     */
    public function add($record, $index = null)
    {
        $index = $this->getRecordKey($record, $index);
        $index = empty($index) ? null : (string) $index;
        parent::add($record, $index);
    }

    /**
     * @param Record $record
     * @param null $index
     * @return string|null
     */
    public function getRecordKey(Record $record, $index = null)
    {
        if ($index) {
            return $this->generateRecordKeyByIndex($record, $index);
        }

        $index = $this->getIndexKey();
        if ($index) {
            return $this->generateRecordKeyByIndex($record, $index);
        }

        $index = $record->getPrimaryKey();
        if (is_array($index)) {
            return implode('-', $index);
        }
        return $index;
    }

    /**
     * @param Record $record
     * @param $index
     * @return mixed
     */
    protected function generateRecordKeyByIndex(Record $record, $index)
    {
        if (is_array($index)) {
            return $record->implodeFields($index);
        }

        return $record->{$index};
    }

    /**
     * @return bool
     */
    public function getIndexKey()
    {
        return $this->_indexKey;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function setIndexKey($key)
    {
        return $this->_indexKey = $key;
    }

    /**
     * @param Record $record
     * @return bool
     */
    public function has($record)
    {
        if ($record instanceof Record) {
            return $this->hasRecord($record);
        }

        return parent::has($record);
    }

    /**
     * @param Record $record
     * @return bool
     */
    public function hasRecord(Record $record)
    {
        $index = $this->getRecordKey($record);

        return parent::has($index) && $this->get($index) == $record;
    }

    /**
     * @param $record
     */
    public function remove($record)
    {
        foreach ($this as $key => $item) {
            if ($item == $record) {
                unset($this[$key]);
            }
        }
    }

    /**
     * When $each is true, each record gets it's delete() method called.
     * Otherwise, a delete query is built for the entire collection
     *
     * @param bool $each
     * @return $this
     */
    public function delete($each = false)
    {
        if (count($this) > 0) {
            if ($each) {
                foreach ($this as $item) {
                    $item->delete();
                }
            } else {
                $primaryKey = $this->getManager()->getPrimaryKey();
                $pk_list = HelperBroker::get('Arrays')->pluck($this, $primaryKey);

                $query = $this->getManager()->newQuery("delete");
                $query->where("$primaryKey IN ?", $pk_list);
                $query->execute();
            }

            $this->clear();
        }

        return $this;
    }
}
