<?php

namespace Nip\Records\Registry;

/**
 * Trait HasModelRegistry
 * @package Nip\Records\Registry
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
    public function setModelRegistry(ModelRegistry $modelRegistry)
    {
        $this->modelRegistry = $modelRegistry;
    }

    protected function initModelRegistry()
    {
        $this->modelRegistry = $this->generateModelRegistry();
    }

    /**
     * @return ModelRegistry
     */
    protected function generateModelRegistry()
    {
        return new ModelRegistry();
    }
}
