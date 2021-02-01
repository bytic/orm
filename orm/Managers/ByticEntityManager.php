<?php

declare(strict_types=1);

namespace ByTIC\ORM\Managers;

/**
 * Class ByticEntityManager
 * @package ByTIC\ORM\Managers
 */
class ByticEntityManager extends EntityManager
{
//    /**
//     * Factory method to create EntityManager instances.
//     *
//     * @param array|Connection $connection   An array with the connection parameters or an existing Connection instance.
//     * @param Configuration    $config       The Configuration instance to use.
//     * @param EventManager     $eventManager The EventManager instance to use.
//     *
//     * @return EntityManager The created EntityManager.
//     *
//     * @throws \InvalidArgumentException
//     * @throws ORMException
//     */
//    public static function create($connection, Configuration $config, EventManager $eventManager = null)
//    {
//        if ( ! $config->getMetadataDriverImpl()) {
//            throw ORMException::missingMappingDriverImpl();
//        }
//
//        $connection = static::createConnection($connection, $config, $eventManager);
//
//        return new EntityManager($connection, $config, $connection->getEventManager());
//    }
//
//    /**
//     * Factory method to create Connection instances.
//     *
//     * @param array|Connection $connection   An array with the connection parameters or an existing Connection instance.
//     * @param Configuration    $config       The Configuration instance to use.
//     * @param EventManager     $eventManager The EventManager instance to use.
//     *
//     * @return Connection
//     *
//     * @throws \InvalidArgumentException
//     * @throws ORMException
//     */
//    protected static function createConnection($connection, Configuration $config, EventManager $eventManager = null)
//    {
//        if (is_array($connection)) {
//            return DriverManager::getConnection($connection, $config, $eventManager ?: new EventManager());
//        }
//
//        if ( ! $connection instanceof Connection) {
//            throw new \InvalidArgumentException(
//                sprintf(
//                    'Invalid $connection argument of type %s given%s.',
//                    is_object($connection) ? get_class($connection) : gettype($connection),
//                    is_object($connection) ? '' : ': "' . $connection . '"'
//                )
//            );
//        }
//
//        if ($eventManager !== null && $connection->getEventManager() !== $eventManager) {
//            throw ORMException::mismatchedEventManager();
//        }
//
//        return $connection;
//    }
}
