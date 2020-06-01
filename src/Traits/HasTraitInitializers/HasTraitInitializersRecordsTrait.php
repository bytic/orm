<?php

namespace Nip\Records\Traits\HasTraitInitializers;

/**
 * Trait HasTraitInitializersRecordsTrait
 * @package Nip\Records\Traits\HasTraitInitializers
 */
trait HasTraitInitializersRecordsTrait
{
    protected $traitInitializers = null;

    /**
     * @return null
     */
    public function getTraitInitializers()
    {
        if ($this->traitInitializers === null) {
            $this->initTraitInitializers();
        }
        return $this->traitInitializers;
    }

    /**
     * @param null $traitInitializers
     */
    public function setTraitInitializers($traitInitializers): void
    {
        $this->traitInitializers = $traitInitializers;
    }

    /**
     * @return bool
     */
    public function hasTraitInitializers()
    {
        return is_array($this->getTraitInitializers());
    }

    protected function initTraitInitializers()
    {
        $this->setTraitInitializers($this->generateTraitInitializers());
    }

    /**
     * @return array
     */
    protected function generateTraitInitializers()
    {
        $traitInitializers = [];
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            $method = 'boot'.class_basename($trait);

            if (method_exists($class, $method) && ! in_array($method, $booted)) {
                forward_static_call([$class, $method]);

                $booted[] = $method;
            }

            if (method_exists($class, $method = 'initialize'.class_basename($trait))) {
                static::$traitInitializers[$class][] = $method;

                static::$traitInitializers[$class] = array_unique(
                    static::$traitInitializers[$class]
                );
            }
        }
        return $traitInitializers;
    }
}
