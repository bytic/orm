<?php

namespace Nip\Records\Instantiator;

use Nip\Records\Locator\ModelLocator;

/**
 * Trait CanInstanceTrait
 * @package Nip\Records\Instantiator
 */
trait CanInstanceTrait
{
    /**
     * @return \Nip\Records\AbstractModels\RecordManager
     */
    public static function instance()
    {
        return ModelLocator::get(static::class);
    }
}
