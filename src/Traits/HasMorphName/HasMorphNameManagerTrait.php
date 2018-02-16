<?php

namespace Nip\Records\Traits\HasMorphName;

/**
 * Trait HasMorphNameManagerTrait
 * @package Nip\Records\Traits\HasMorphName
 */
trait HasMorphNameManagerTrait
{

    /**
     * @var null|string
     */
    protected $morphName = null;

    /**
     * @return null|string
     */
    public function getMorphName(): string
    {
        if ($this->morphName === null) {
            $this->initMorphName();
        }
        return $this->morphName;
    }

    /**
     * @param null|string $morphName
     */
    public function setMorphName(string $morphName)
    {
        $this->morphName = $morphName;
    }

    protected function initMorphName()
    {
        $name = method_exists($this, 'generateMorphName') ? $this->generateMorphName() : $this->getTable();
        $this->setMorphName($name);
    }
}
