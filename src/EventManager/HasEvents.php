<?php

namespace Nip\Records\EventManager;

use Nip\Records\AbstractModels\Record;
use Nip\Records\EventManager\Events\Event;

/**
 * Trait HasEvents
 * @package Nip\Records\Events
 */
trait HasEvents
{
    use HasObservables;

    /**
     * Register a retrieved model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function retrieved($callback)
    {
        static::registerModelEvent('retrieved', $callback);
    }

    /**
     * Register a saving model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function saving($callback)
    {
        static::registerModelEvent('saving', $callback);
    }

    /**
     * Register a saved model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function saved($callback)
    {
        static::registerModelEvent('saved', $callback);
    }

    /**
     * Register an updating model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function updating($callback)
    {
        static::registerModelEvent('updating', $callback);
    }

    /**
     * Register an updated model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function updated($callback)
    {
        static::registerModelEvent('updated', $callback);
    }

    /**
     * Register a creating model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function creating($callback)
    {
        static::registerModelEvent('creating', $callback);
    }

    /**
     * Register a created model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function created($callback)
    {
        static::registerModelEvent('created', $callback);
    }

    /**
     * Register a replicating model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function replicating($callback)
    {
        static::registerModelEvent('replicating', $callback);
    }

    /**
     * Register a deleting model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function deleting($callback)
    {
        static::registerModelEvent('deleting', $callback);
    }

    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     * @return void
     */
    public static function deleted($callback)
    {
        static::registerModelEvent('deleted', $callback);
    }

    /**
     * @param $event
     * @param $callback
     */
    protected static function registerModelEvent($event, $callback)
    {
        static::eventManager()->registerModelEvent($event, static::class, $callback);
    }

    /**
     * Fire the given event for the model.
     *
     * @param string $event
     * @param Record $record
     * @return mixed
     */
    protected function fireModelEvent($event, Record $record)
    {
        $event = Event::create($event, $this)->withRecord($record);
        return static::eventManager()->dispatch($event);
    }

    /**
     * @return EventManager
     */
    protected static function eventManager()
    {
        return EventManager::instance();
    }
}
