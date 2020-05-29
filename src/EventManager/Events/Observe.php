<?php

namespace Nip\Records\EventManager\Events;

/**
 * Class Observe
 * @package Nip\Records\EventManager\Events
 */
class Observe
{
    public const RETRIEVED = 'retrieved';

    /**
     * @return string[]
     */
    public static function all()
    {
        return [
            static::RETRIEVED
        ];
    }
}
