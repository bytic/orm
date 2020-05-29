<?php

namespace Nip\Records\EventManager;

/**
 * Trait HasObservables
 * @package Nip\Records\Events
 */
trait HasObservables
{
    /**
     * User exposed observable events.
     *
     * These are extra user-defined events observers may subscribe to.
     *
     * @var array
     */
    protected $observables = [];

    /**
     * Get the observable event names.
     *
     * @return array
     */
    public function getObservableEvents()
    {
        return array_merge(
            Observe::all(),
            $this->observables
        );
    }

    /**
     * Set the observable event names.
     *
     * @param array $observables
     * @return $this
     */
    public function setObservableEvents(array $observables)
    {
        $this->observables = $observables;

        return $this;
    }

    /**
     * Add an observable event name.
     *
     * @param array|mixed $observables
     * @return void
     */
    public function addObservableEvents($observables)
    {
        $this->observables = array_unique(
            array_merge(
                $this->observables,
                is_array($observables) ? $observables : func_get_args()
            )
        );
    }

    /**
     * Remove an observable event name.
     *
     * @param array|mixed $observables
     * @return void
     */
    public function removeObservableEvents($observables)
    {
        $this->observables = array_diff(
            $this->observables,
            is_array($observables) ? $observables : func_get_args()
        );
    }
}