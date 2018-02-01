<?php

namespace Nip\Records\Traits\Relations;

use Nip\Records\Relations\HasMany;
use Nip\Records\Relations\Relation;
use Nip\Records\Traits\AbstractTrait\RecordTrait;

/**
 * Trait HasRelationsRecordTrait
 * @package Nip\Records\Traits\Relations
 *
 * @method HasRelationsRecordsTrait getManager
 */
trait HasRelationsRecordTrait
{
    use RecordTrait;

    /**
     * The loaded relationships for the model.
     * @var array
     */
    protected $relations = [];

    public function saveRelations()
    {
        $relations = $this->getRelations();
        foreach ($relations as $relation) {
            /** @var Relation $relation */
            $relation->save();
        }
    }

    /**
     * @return array
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @param string $name
     * @param $arguments
     * @return bool|\Nip\Records\AbstractModels\Record|\Nip\Records\Collections\Collection
     */
    protected function isCallRelationOperation($name, $arguments = [])
    {
        if (substr($name, 0, 3) == "get") {
            $relation = $this->getRelation(substr($name, 3));
            if ($relation) {
                return $relation->getResults();
            }
        }

        return false;
    }

    /**
     * @param $relationName
     * @return Relation|HasMany|null
     */
    public function getRelation($relationName)
    {
        if (!$this->hasRelation($relationName)) {
            $this->initRelation($relationName);
        }

        return $this->relations[$relationName];
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasRelation($key)
    {
        return array_key_exists($key, $this->relations);
    }

    /**
     * @param $relationName
     */
    public function initRelation($relationName)
    {
        if (!$this->getManager()->hasRelation($relationName)) {
            return;
        }
        $this->relations[$relationName] = $this->newRelation($relationName);
    }

    /**
     * @param string $relationName
     * @return Relation|null
     */
    public function newRelation($relationName)
    {
        $relation = clone $this->getManager()->getRelation($relationName);
        $relation->setItem($this);

        return $relation;
    }
}
