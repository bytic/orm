<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema\Elements;

use Exception;

/**
 * Class EntitySchema
 * @package ByTIC\ORM\Schema\Objects
 */
class EntitySchema extends AbstractSchema
{
    public const ROLE = 'role';
    public const ALIASES = 'aliases';
    public const ENTITY = 'entity';
    public const REPOSITORY = 'repository';
    public const TABLE = 'table';
    public const DATABASE = 'database';
    public const FIELDS = 'fields';
    public const IDENTIFIER = 'identifier';
    public const RELATIONS = 'relations';

    protected $role;

    protected $aliases = [];

    protected $table;

    protected $entity;

    protected $repository;

    protected $identifier;

    /**
     * @var FieldCollection
     */
    protected $fields;

    /**
     * EntitySchema constructor.
     */
    public function __construct()
    {
        $this->fields = new FieldCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getRole(): string
    {
        return $this->role;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function setTable($table): void
    {
        $this->table = $table;
    }

    public function getFields(): FieldCollection
    {
        return $this->fields;
    }

    /**
     * Checks if the field is unique.
     *
     * @param string $fieldName The field name.
     *
     * @return boolean TRUE if the field is unique, FALSE otherwise.
     */
    public function isUniqueField($fieldName)
    {
        $mapping = $this->getFieldMapping($fieldName);

        return false !== $mapping && isset($mapping['unique']) && $mapping['unique'];
    }

    /**
     * Checks if the field is not null.
     *
     * @param string $fieldName The field name.
     *
     * @return boolean TRUE if the field is not null, FALSE otherwise.
     */
    public function isNullable($fieldName)
    {
        $mapping = $this->getFieldMapping($fieldName);

        return false !== $mapping && isset($mapping['nullable']) && $mapping['nullable'];
    }

    /**
     * Gets the mapping of a (regular) field that holds some data but not a
     * reference to another object.
     *
     * @param string $fieldName The field name.
     *
     * @return array The field mapping.
     *
     * @throws MappingException
     */
    public function getFieldMapping($fieldName)
    {
        if (!isset($this->fieldMappings[$fieldName])) {
            throw MappingException::mappingNotFound($this->name, $fieldName);
        }

        return $this->fieldMappings[$fieldName];
    }

    public function getIndexes(): \Generator
    {
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
}