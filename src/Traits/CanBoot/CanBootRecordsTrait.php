<?php

namespace Nip\Records\Traits\CanBoot;

/**
 * Trait CanBootRecordsTrait
 * @package Nip\Records\Traits\CanBoot
 */
trait CanBootRecordsTrait
{
    protected $booted = false;

    public function bootIfNotBooted()
    {
        if ($this->booted !== false) {
            return;
        }
//        $this->fireModelEvent('booting', false);

        $this->booting();
        $this->boot();
        $this->booted();

//        $this->fireModelEvent('booted', false);
        $this->booted = true;
    }

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected function booting()
    {
        //
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected function boot()
    {
        $this->bootTraits();
    }

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected function booted()
    {
        //
    }
}
