<?php

namespace Nip\Records\EventManager\Dispatcher;

/**
 * Trait HasDispatcher
 * @package Nip\Records\EventManager\Dispatcher
 */
trait HasDispatcher
{

    /**
     * The event dispatcher instance.
     *
     * @var Dispatcher
     */
    protected static $dispatcher = null;

    /**
     * Get the event dispatcher instance.
     *
     * @return Dispatcher
     */
    public static function getEventDispatcher()
    {
        if (static::$dispatcher === null) {
            static::$dispatcher = static::generateEventDispatcher();
        }
        return static::$dispatcher;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param Dispatcher $dispatcher
     * @return void
     */
    public static function setEventDispatcher(Dispatcher $dispatcher)
    {
        static::$dispatcher = $dispatcher;
    }

    /**
     * Unset the event dispatcher for models.
     *
     * @return void
     */
    public static function unsetEventDispatcher()
    {
        static::$dispatcher = null;
    }

    /**
     * @return Dispatcher
     */
    protected static function generateEventDispatcher()
    {
        return new Dispatcher();
    }
}