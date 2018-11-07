<?php

namespace Nip\Records\Locator\ModelLocator\Traits;

use Nip\Records\Locator\Exceptions\InvalidModelException;
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
    public static function get($entityManager)
    {
        return self::instance()->getManager($entityManager);
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
