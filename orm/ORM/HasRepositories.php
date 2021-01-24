<?php

declare(strict_types=1);

namespace ByTIC\ORM\ORM;

use ByTIC\ORM\Exception\ORMException;
use ByTIC\ORM\Repositories\RepositoryInterface;

/**
 * Trait HasRepositories
 * @package ByTIC\ORM\ORM
 */
trait HasRepositories
{
    /** @var RepositoryInterface[] */
    protected $repositories = [];

    /**
     * Automatically resolve role based on object name or instance.
     *
     * @param string|object $entity
     * @return string
     */
    public function resolveRole($entity): string
    {
        if (is_object($entity)) {
            $node = $this->getHeap()->get($entity);
            if ($node !== null) {
                return $node->getRole();
            }

            $class = get_class($entity);
            if (!$this->getSchema()->defines($class)) {
                throw new ORMException("Unable to resolve role of `$class`");
            }

            $entity = $class;
        }

        return $this->schema->resolveAlias($entity);
    }

    /**
     * @inheritdoc
     */
    public function getRepository($entity, $persistentManagerName = null): RepositoryInterface
    {
        $role = $this->resolveRole($entity);
        if (isset($this->repositories[$role])) {
            return $this->repositories[$role];
        }

//        $select = null;
//
//        if ($this->schema->define($role, Schema::TABLE) !== null) {
//            $select = new Select($this, $role);
//            $select->constrain($this->getSource($role)->getConstrain());
//        }

        return $this->repositories[$role] = $this->factory->repository($this, $this->schema, $role);
    }
}