<?php

namespace Nip\Records\Collections;

use Nip\Records\Collections\Collection as RecordCollection;
use Nip\Records\Record as Record;
use Nip\Records\Relations\HasOneOrMany as Relation;
use Nip\Records\Relations\Traits\HasCollectionResults;

/**
 * Class Associated
 * @package Nip\Records\Collections
 */
class Associated extends RecordCollection
{
    /**
     * @var Relation
     */
    protected $_withRelation;

    /**
     * @var Record
     */
    protected $_item;

    /**
     * @param HasCollectionResults|Relation $relation
     */
    public function initFromRelation($relation)
    {
        parent::initFromRelation($relation);
        $this->setWithRelation($relation);

        if ($relation->hasItem()) {
            $this->setItem($relation->getItem());
        }
    }

    public function save()
    {
        return $this->getWithRelation()->save();
    }

    /**
     * @return Relation
     */
    public function getWithRelation()
    {
        return $this->_withRelation;
    }

    /**
     * @param Relation $relation
     */
    public function setWithRelation($relation)
    {
        $this->_withRelation = $relation;
    }

    /**
     * @return Record
     */
    public function getItem()
    {
        return $this->_item;
    }

    /**
     * @param Record $item
     */
    public function setItem($item)
    {
        $this->_item = $item;
    }
}
