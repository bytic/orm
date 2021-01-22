<?php

namespace Nip\Records\Relations\Traits;

use Nip\Records\Record;
use Nip\Records\Traits\Relations\HasRelationsRecordTrait;

/**
 * Trait HasItemTrait
 * @package Nip\Records\Relations\Traits
 */
trait HasItemTrait
{
    /**
     * @var Record
     */
    protected $item;

    /**
     * @return Record
     */
    public function getItem()
    {
        return $this->item;
    }

    public function hasItem(): bool
    {
        return $this->item instanceof Record;
    }

    /**
     * @param Record|HasRelationsRecordTrait $item
     * @return $this
     */
    public function setItem(Record $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemRelationPrimaryKey()
    {
        $key = $this->getPrimaryKey();
        return $this->getItemValue($key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getItemValue($key)
    {
        return $this->getItem()->{$key};
    }
}
