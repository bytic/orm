<?php

namespace Nip\Records\EventManager\Dispatcher;

use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Class Dispatcher
 * @package Nip\Records\EventManager\Dispatcher
 */
class Dispatcher implements \Psr\EventDispatcher\EventDispatcherInterface
{

    /**
     * @var ListenerProviderInterface
     */
    protected $listenerProvider = null;

    /**
     * EventDispatcher constructor.
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(ListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event)
    {
        // If the event is already stopped, this method becomes a no-op.
        if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
            return $event;
        }

        foreach ($this->listenerProvider->getListenersForEvent($event) as $listener) {
            // Technically this has an extraneous stopped-check after the last listener,
            // but that doesn't violate the spec since it's still technically checking
            // before each listener is called, given the check above.
            try {
                $listener($event);
                if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                    break;
                }
            } catch (\Exception $e) {
                // We do not catch Errors here, because Errors indicate the developer screwed up in
                // some way. Let those bubble up because they should just become fatals.
                throw $e;
            }
        }
        return $event;
    }
}
