<?php

declare(strict_types=1);

namespace ByTIC\ORM\ORM;

use ByTIC\ORM\ORM;
use RuntimeException;

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
     * @return mixed
     */
    public static function instance()
    {
        return static::resolveInstance();
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param object|string $name
     * @return mixed
     */
    protected static function resolveInstance(): ORM
    {
        if (static::$instance !== null) {
            return static::$instance;
        }

        return static::$instance = new ORM();
    }
}