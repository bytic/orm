<?php

namespace Nip\Records\EventManager\Events;

/**
 * Class Observe
 * @package Nip\Records\EventManager\Events
 */
class Observe
{
    public const RETRIEVED = 'retrieved';

    public const CREATING = 'creating';
    public const CREATED = 'created';

    public const UPDATING = 'updating';
    public const UPDATED = 'updated';

    public const DELETING = 'deleting';
    public const DELETED = 'deleted';

    /**
     * @return string[]
     */
    public static function all()
    {
        return [
            static::RETRIEVED,
            static::CREATING,
            static::CREATED,
            static::UPDATING,
            static::UPDATED,
            static::DELETING,
            static::DELETED,
        ];
    }
}
