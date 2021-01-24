<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema\Elements;

use ByTIC\ORM\Exception\SchemaException;

/**
 * Class FieldSchema
 * @package ByTIC\ORM\Schema\Objects
 */
class FieldSchema
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var mixed
     */
    protected $type = 'string';

    /**
     * @var boolean
     */
    protected $unique = false;

    /**
     * @var boolean
     */
    protected $nullable = false;

    /**
     * FieldSchema constructor.
     * @param $name
     * @param $type
     */
    public function __construct($name, $type = 'string')
    {
        $this->name = $name;
        $this->type = $type;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @param bool $unique
     */
    public function setUnique(bool $unique): void
    {
        $this->unique = $unique;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     */
    public function setNullable(bool $nullable): void
    {
        $this->nullable = $nullable;
    }
}