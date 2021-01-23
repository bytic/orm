<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema;

use ByTIC\ORM\Exception\SchemaException;
use ByTIC\ORM\Schema\Objects\EntitySchema;
use ByTIC\ORM\Schema\Objects\RelationSchema;

/**
 * Class Schema
 * @package ByTIC\ORM\Schema
 */
final class Schema implements \ByTIC\ORM\Schema\SchemaInterface
{
    /** @var array */
    protected $aliases = [];

    /** @var array */
    protected $entities = [];

    /**
     * @param array $schema
     */
    public function __construct(array $schema = [])
    {
        // split into two?
        [$this->entities, $this->aliases] = $this->normalize($schema);
    }

    /**
     * @inheritdoc
     */
    public function getRoles(): array
    {
        return array_keys($this->entities);
    }

    public function getRelations(string $role): array
    {
        // TODO: Implement getRelations() method.
    }

    public function defines(string $role): bool
    {
        return isset($this->schema[$role]) || isset($this->aliases[$role]);
    }

    /**
     * @inheritDoc
     */
    public function define(string $role, int $property)
    {
        $role = $this->resolveAlias($role) ?? $role;

        if (!isset($this->schema[$role])) {
            throw new SchemaException("Undefined schema `{$role}`, not found");
        }

        return $this->schema[$role]->{$property} ?? null;
    }

    public function defineRelation(string $role, string $relation): array
    {
        // TODO: Implement defineRelation() method.
    }

    /**
     * @param $alias
     * @param $real
     */
    public function addAlias($alias, $real)
    {
        $this->aliases[$alias] = $real;
    }

    /**
     * @inheritdoc
     */
    public function resolveAlias(string $entity): ?string
    {
        // walk throught all children until parent entity found
        while (isset($this->aliases[$entity])) {
            $entity = $this->aliases[$entity];
        }

        return $entity;
    }

    /**
     * Automatically replace class names with their aliases.
     *
     * @param array $schema
     * @return array Pair of [schema, aliases]
     */
    protected function normalize(array $schema): array
    {
        $result = $aliases = [];

        foreach ($schema as $key => $item) {
            $role = $key;

            if (class_exists($key)) {
                $role = $item[EntitySchema::ROLE] ?? $key;
                if ($role !== $key) {
                    $aliases[$key] = $role;
                }
            }

            if ($item[EntitySchema::ENTITY] !== $role && class_exists($item[EntitySchema::ENTITY])) {
                $aliases[$item[EntitySchema::ENTITY]] = $role;
            }

            unset($item[EntitySchema::ROLE]);
            $result[$role] = $item;
        }

        // normalizing relation associations
        foreach ($result as &$item) {
            if (isset($item[EntitySchema::RELATIONS])) {
                $item[EntitySchema::RELATIONS] = iterator_to_array(
                    $this->normalizeRelations(
                        $item[EntitySchema::RELATIONS],
                        $aliases
                    )
                );
            }

            unset($item);
        }

        return [$result, $aliases];
    }

    /**
     * @param array $relations
     * @param array $aliases
     * @return \Generator
     */
    private function normalizeRelations(array $relations, array $aliases): \Generator
    {
        foreach ($relations as $name => &$rel) {
            $target = $rel[RelationSchema::TARGET];
            while (isset($aliases[$target])) {
                $target = $aliases[$target];
            }

            $rel[RelationSchema::TARGET] = $target;

            yield $name => $rel;
        }
    }
}