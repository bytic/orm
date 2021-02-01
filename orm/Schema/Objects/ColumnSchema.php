<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema\Objects;

/**
 * Class ColumnSchema
 * @package ByTIC\ORM\Schema\Objects
 */
class ColumnSchema
{
    public const TYPE_STRING = 'string';

    /**
     * @var string
     */
    public $name;

    /**
     * @var mixed
     */
    public $type = self::TYPE_STRING;

    /**
     * @var int
     */
    public $length;

    /**
     * The precision for a decimal (exact numeric) column (Applies only for decimal column).
     *
     * @var int
     */
    public $precision = 0;

    /**
     * The scale for a decimal (exact numeric) column (Applies only for decimal column).
     *
     * @var int
     */
    public $scale = 0;

    /**
     * @var bool
     */
    public $unique = false;

    /**
     * @var bool
     */
    public $nullable = false;

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return $this->unique;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
