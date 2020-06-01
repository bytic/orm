<?php

namespace Nip\Records\Instantiator;

/**
 * Trait HasInstantiatorTrait
 * @package Nip\Records\Instantiator
 */
trait HasInstantiatorTrait
{
    protected $instantiator = null;

    /**
     * @return Instantiator
     */
    public function getInstantiator()
    {
        if ($this->instantiator === null) {
            $this->setInstantiator($this->generateInstantiator());
        }
        return $this->instantiator;
    }

    /**
     * @param Instantiator $instantiator
     */
    public function setInstantiator($instantiator): void
    {
        $this->instantiator = $instantiator;
    }

    /**
     * @return Instantiator
     */
    protected function generateInstantiator()
    {
        $instantiator = new Instantiator();
        if (method_exists($this, 'getModelRegistry')) {
            $instantiator->setModelRegistry($this->getModelRegistry());
        }
        return $instantiator;
    }
}