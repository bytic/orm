<?php

namespace Nip\Records\Traits\CanBoot;

/**
 * Trait CanBootRecordsTrait
 * @package Nip\Records\Traits\CanBoot
 */
trait CanBootRecordsTrait
{
    protected function bootIfNotBooted()
    {
//        $this->fireModelEvent('booting', false);

        static::booting();
        static::boot();
        static::booted();

//        $this->fireModelEvent('booted', false);
    }

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booting()
    {
        //
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
//        static::bootTraits();
    }

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        //
    }
}