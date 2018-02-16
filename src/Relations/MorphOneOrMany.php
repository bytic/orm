<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;
use Nip\Records\Relations\Traits\HasMorphTypeTrait;

/**
 * Class MorphOneOrMany
 * @package Nip\Records\Relations
 */
abstract class MorphOneOrMany extends HasOneOrMany
{
    use HasMorphTypeTrait;

    /**
     * The class name of model manager
     * This value is saved in the morph type collumn
     *
     * @var string
     */
    protected $morphValue = null;

    /**
     * @return string
     */
    public function getMorphValue(): string
    {
        if ($this->morphValue == null) {
            $this->initMorphClass();
        }
        return $this->morphValue;
    }

    /**
     * @param string $morphValue
     */
    public function setMorphValue(string $morphValue)
    {
        $this->morphValue = $morphValue;
    }

    /**
     * @return string
     */
    protected function initMorphClass()
    {
        $this->setMorphValue(
            $this->getManager()->getMorphName()
        );
    }
}
