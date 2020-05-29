<?php

namespace Nip\Records\EventManager\ListenerProviders;

/**
 * Class LifecycleListenerProvider
 * @package Nip\Records\EventManager\ListenerProviders;
 */
class LifecycleListenerProvider implements \Psr\EventDispatcher\ListenerProviderInterface
{
    /**
     * Map of registered listeners.
     * <event> => <listeners>
     *
     * @var object[][]
     */
    protected $listeners = [];

    /**
     * @param $events
     * @param $listener
     */
    public function listen($events, $listener)
    {
        // Picks the hash code related to that listener
        $hash = spl_object_hash($listener);

        foreach ((array)$events as $event) {
            // Overrides listener if a previous one was associated already
            // Prevents duplicate listeners on same event (same instance only)
            $this->listeners[$event][$hash] = $this->makeListener($listener);
        }
    }

    public function getListenersForEvent(object $event): iterable
    {
        $eventName = $event;
        $listeners = $this->listeners[$eventName] ?? [];

        return $listeners;
    }
}
