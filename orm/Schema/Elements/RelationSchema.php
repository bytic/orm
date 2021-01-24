<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema\Elements;

/**
 * Class RelationSchema
 * @package ByTIC\ORM\Schema\Objects
 */
class RelationSchema extends AbstractSchema
{
    public const TYPE = 'type';
    public const TARGET = 'target';

    // Relation types (default)
    public const HAS_ONE = 10;
    public const HAS_MANY = 11;
    public const BELONGS_TO = 12;

    // Morphed relations
    public const BELONGS_TO_MORPHED = 20;
    public const MORPHED_HAS_ONE = 21;
    public const MORPHED_HAS_MANY = 23;

    /**
     * Identifies a one-to-one association.
     */
    const ONE_TO_ONE = 1;

    /**
     * Identifies a many-to-one association.
     */
    const MANY_TO_ONE = 2;

    /**
     * Identifies a one-to-many association.
     */
    const ONE_TO_MANY = 4;

    /**
     * Identifies a many-to-many association.
     */
    const MANY_TO_MANY = 8;

    /**
     * Combined bitmask for to-one (single-valued) associations.
     */
    const TO_ONE = 3;

    /**
     * Combined bitmask for to-many (collection-valued) associations.
     */
    const TO_MANY = 12;
}
