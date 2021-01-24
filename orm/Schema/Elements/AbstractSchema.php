<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema\Elements;

/**
 * Class AbstractSchema
 * @package ByTIC\ORM\Schema\Objects
 */
abstract class AbstractSchema implements \Serializable
{
    /**
     * EntitySchema constructor.
     * @param array $data
     */
    protected function populateFromData($data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public static function fromArray(array $data): self
    {
        $item = new static();
        $item->populateFromData($data);
        return $item;
    }

    public function __sleep(): array
    {
        // This metadata is always serialized/cached.
        return [
            'role',
            'table',
            'fields',
        ];
    }

    public function serialize()
    {
        // TODO: Implement serialize() method.
    }

    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
}