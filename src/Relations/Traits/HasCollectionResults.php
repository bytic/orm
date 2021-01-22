<?php

namespace Nip\Records\Relations\Traits;

use Nip\Records\Collections\Associated as AssociatedCollection;
use Nip\Records\Collections\Collection;

/**
 * Trait HasCollectionResults
 * @package Nip\Records\Relations\Traits
 *
 * @method string getParam($name)
 */
trait HasCollectionResults
{
    /**
     * @return Collection
     */
    public function newCollection(): Collection
    {
        $collection = $this->getWith()->newCollection();
        /** @var Collection $collection */
        $collection->initFromRelation($this);

        return $collection;
    }

    /**
     * @return AssociatedCollection
     */
    public function newAssociatedCollection()
    {
        $class = $this->getCollectionClass();
        $collection = new $class();
        /** @var AssociatedCollection $collection */
        $collection->initFromRelation($this);

        return $collection;
    }

    /**
     * @return mixed|string
     */
    public function getCollectionClass()
    {
        $collection = $this->getParam('collection');
        if ($collection) {
            return $collection;
        }

        return AssociatedCollection::class;
    }
}
