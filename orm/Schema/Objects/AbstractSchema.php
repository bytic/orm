<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema\Objects;

/**
 * Class AbstractSchema
 * @package ByTIC\ORM\Schema\Objects
 */
abstract class AbstractSchema
{
    /**
     * EntitySchema constructor.
     * @param array $data
     */
    protected function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public static function fromArray(array $data): self
    {
        return new static($data);
    }
}
