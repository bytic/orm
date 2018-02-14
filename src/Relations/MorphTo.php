<?php

namespace Nip\Records\Relations;

/**
 * Class MorphToMany
 * @package Nip\Records\Relations
 */
class MorphTo extends BelongsTo
{
    protected $morphPrefix = 'item';

    protected $morphTypeField = null;

    /**
     * @param $params
     */
    public function addParams($params)
    {
        $this->checkParamMorphPrefix($params);
        parent::addParams($params);
    }

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
    public function setMorphPrefix(string $morphPrefix): void
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
     */
    public function setMorphTypeField($morphTypeField): void
    {
        $this->morphTypeField = $morphTypeField;
    }
}
