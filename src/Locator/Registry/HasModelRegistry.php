<?php

namespace Nip\Records\Locator\Registry;

/**
 * Trait HasModelRegistry
 * @package Nip\Records\Locator\Registry
 */
trait HasModelRegistry
{
    /**
     * @var ModelRegistry;
     */
    protected $modelRegistry = null;

    /**
     * @return ModelRegistry
     */
    public function getModelRegistry()
    {
        if ($this->modelRegistry === null) {
            $this->initModelRegistry();
        }
        return $this->modelRegistry;
    }

    /**
     * @param modelRegistry $modelRegistry
     */
    protected function setModelRegistry(ModelRegistry $modelRegistry)
    {
        $this->modelRegistry = $modelRegistry;
    }


    protected function initModelRegistry()
    {
        $this->modelRegistry = (new ModelRegistry);
    }
}
