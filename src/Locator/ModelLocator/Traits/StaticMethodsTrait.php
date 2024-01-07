<?php

namespace Nip\Records\Locator\ModelLocator\Traits;

use Nip\Records\AbstractModels\RecordManager;

/**
 * Trait StaticMethodsTrait
 * @package Nip\Records\Locator\ModelLocator\Traits
 */
trait StaticMethodsTrait
{
    /**
     * @var self
     */
    protected static $instance;

    /**
     * @param $entityManager
     * @return RecordManager
     */
    public static function get($entityManager, $default = null)
    {
        return self::instance()->getManager($entityManager, $default);
    }

    /**
     * @param $entityManager
     * @return string
     */
    public static function class($entityManager)
    {
        return self::instance()->getManagerClass($entityManager);
    }

    /**
     * @param $entity
     * @return RecordManager
     */
    public static function for($entity)
    {
        if (is_object($entity)) {
            $entity = method_exists($entity, 'getClassName') ? $entity->getClassName() : get_class($entity);
        }
        $entityName = ucfirst(inflector()->pluralize($entity));
        return self::get($entityName);
    }

    /**
     * @param string $alias
     * @param RecordManager $entityManager
     * @return void`
     */
    public static function set($alias, $entityManager)
    {
        self::instance()->getModelRegistry()->set($alias, $entityManager);
    }

    /**
     * Singleton
     *
     * @return self
     */
    public static function instance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
