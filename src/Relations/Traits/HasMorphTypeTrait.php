<?php

namespace Nip\Records\Relations\Traits;

/**
 * Trait HasMorphTypeTrait
 * @package Nip\Records\Relations\Traits
 */
trait HasMorphTypeTrait
{
    protected $morphPrefix = 'parent';

    protected $morphTypeField = null;


    /**
     * @param $params
     */
    public function checkParamMorphPrefix($params)
    {
        if (isset($params['morphPrefix'])) {
            $this->setMorphPrefix($params['morphPrefix']);
            unset($params['morphPrefix']);
        }
    }

    /**
     * @return string
     */
    public function getMorphPrefix(): string
    {
        return $this->morphPrefix;
    }

    /**
     * @param string $morphPrefix
     */
    public function setMorphPrefix(string $morphPrefix)
    {
        $this->morphPrefix = $morphPrefix;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return string
     */
    public function generateFK()
    {
        return $this->getMorphPrefix() . '_id';
    }

    /**
     * @return null
     */
    public function getMorphTypeField()
    {
        if ($this->morphTypeField === null) {
            $this->setMorphTypeField(
                $this->getMorphPrefix() . '_type'
            );
        }
        return $this->morphTypeField;
    }

    /**
     * @param null $morphTypeField
     * @return void
     */
    public function setMorphTypeField($morphTypeField)
    {
        $this->morphTypeField = $morphTypeField;
    }
}
