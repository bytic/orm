<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema;

/**
 * Interface SchemaInterface
 * @package ByTIC\ORM\Schema
 */
interface SchemaInterface
{
    /*
     * Various segments of schema.
     */
    public const MAPPER = 'mapper';
    public const SOURCE = 'source';
//    public const FIND_BY_KEYS = 'role';
    public const CHILDREN = 'children';
    public const CONSTRAIN = 'constrain';
    public const TYPECAST = 'typecast';
    public const SCHEMA = 'schema';

    /**
     * Return all roles defined within the schema.
     *
     * @return array
     */
    public function getRoles(): array;

    /**
     * Get name of relations associated with given entity role.
     *
     * @param string $role
     * @return array
     */
    public function getRelations(string $role): array;

    /**
     * Check if the given role has a definition within the schema.
     *
     * @param string $role
     * @return bool
     */
    public function defines(string $role): bool;

    /**
     * Define schema value.
     *
     * Example: $schema->define(User::class, SchemaInterface::DATABASE);
     *
     * @param string $role
     * @param int $property See ORM constants.
     * @return mixed
     */
    public function define(string $role, int $property);

    /**
     * Define options associated with specific entity relation.
     *
     * @param string $role
     * @param string $relation
     * @return array
     */
    public function defineRelation(string $role, string $relation): array;

    /**
     * Resolve the role name using entity class name.
     *
     * @param string $role
     * @return null|string
     */
    public function resolveAlias(string $role): ?string;
}