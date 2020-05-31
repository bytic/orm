<?php

namespace Nip\Records\EventManager;

use Nip\Records\EventManager\Dispatcher\Dispatcher;
use Nip\Records\EventManager\ListenerProviders\LifecycleListenerProvider;
use Nip\Records\RecordManager;
use Nip\Utility\Traits\SingletonTrait;

/**
 * Class EventManager
 * @package Nip\Records\EventManager
 */
class EventManager implements \Psr\EventDispatcher\EventDispatcherInterface
{
    use SingletonTrait;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    protected $listenerProvider;

    public function __construct()
    {
        $this->listenerProvider = new LifecycleListenerProvider();
        $this->dispatcher = new Dispatcher($this->listenerProvider);
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event)
    {
        return $this->dispatcher->dispatch($event);
    }

    /**
     * @param string $event
     * @param RecordManager|HasEvents $manager
     * @param $callback
     */
    public function registerModelEvent($event, $manager, $callback)
    {
        $eventName = static::eventName($event, $manager);
        $this->listen($eventName, $callback);
    }

    /**
     * @param $stage
     * @param RecordManager|string $manager
     * @return string
     */
    public static function eventName($stage, $manager)
    {
        $manager = is_object($manager) ? $manager->getClassName() : (string) $manager;
        return 'orm.'
            . $stage
            . ': ' . $manager;
    }

    /**
     * @param $events
     * @param $listener
     */
    protected function listen($event, $listener)
    {
        $this->listener()->listen($event, $listener);
    }

    /**
     * @return Dispatcher
     */
    protected function dispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @return LifecycleListenerProvider
     */
    protected function listener()
    {
        return $this->listenerProvider;
    }
}
