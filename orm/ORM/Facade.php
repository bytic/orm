<?php

declare(strict_types=1);

namespace ByTIC\ORM\ORM;

use ByTIC\ORM\ORM;

/**
 * Trait Facade
 * @package ByTIC\ORM\ORM
 */
trait Facade
{
    /**
     * @var ORM
     */
    private static $instance = null;

    /**
     * Get the root object behind the facade.
     *
     * @return ORM
     */
    public static function instance()
    {
        return static::resolveInstance();
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param object|string $name
     * @return ORM
     */
    protected static function resolveInstance(): ORM
    {
        if (static::$instance !== null) {
            return static::$instance;
        }

        return static::$instance = new ORM();
    }
}