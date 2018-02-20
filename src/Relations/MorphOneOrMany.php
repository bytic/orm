<?php

namespace Nip\Records\Relations;

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
            $this->initMorphValue();
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
    protected function initMorphValue()
    {
        $this->setMorphValue(
            $this->getManager()->getMorphName()
        );
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public function addParams($params)
    {
        $this->checkParamMorphPrefix($params);
        parent::addParams($params);
    }
}
