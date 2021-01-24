<?php

declare(strict_types=1);

namespace ByTIC\ORM\Managers;

use ByTIC\ORM\Repositories\RepositoryInterface;

/**
 * Class EntityManager
 * @package ByTIC\ORM\Managers
 */
class EntityManager implements EntityManagerInterface
{
    public function find($className, $id)
    {
        // TODO: Implement find() method.
    }

    public function persist($object)
    {
        // TODO: Implement persist() method.
    }

    public function remove($object)
    {
        // TODO: Implement remove() method.
    }

    public function merge($object)
    {
        // TODO: Implement merge() method.
    }

    public function clear($objectName = null)
    {
        // TODO: Implement clear() method.
    }

    public function detach($object)
    {
        // TODO: Implement detach() method.
    }

    public function refresh($object)
    {
        // TODO: Implement refresh() method.
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }

    public function getRepository($className): RepositoryInterface
    {
        // TODO: Implement getRepository() method.
    }

    public function getClassMetadata($className)
    {
        // TODO: Implement getClassMetadata() method.
    }

    public function getMetadataFactory()
    {
        // TODO: Implement getMetadataFactory() method.
    }

    public function initializeObject($obj)
    {
        // TODO: Implement initializeObject() method.
    }

    public function contains($object)
    {
        // TODO: Implement contains() method.
    }

    /**
     * @param $className
     */
    public function hasRepository($className): bool
    {
    }
}
