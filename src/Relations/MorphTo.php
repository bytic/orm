<?php

namespace Nip\Records\Relations;

use Nip\Records\AbstractModels\Record;
use Nip\Records\Relations\Exceptions\ModelNotLoadedInRelation;

/**
 * Class MorphToMany
 * @package Nip\Records\Relations
 */
class MorphTo extends BelongsTo
{
    protected $morphPrefix = 'item';

    protected $morphTypeField = null;

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return string
     * @throws ModelNotLoadedInRelation
     */
    public function getWithClass()
    {
        $type = $this->getMorphType();
        $typePlural = inflector()->pluralize($type);
        return $typePlural;
    }

    /**
     * @return mixed
     * @throws ModelNotLoadedInRelation
     */
    public function getMorphType()
    {
        if ($this->getItem() instanceof Record) {
            return $this->getItem()->{$this->getMorphTypeField()};
        }
        throw new ModelNotLoadedInRelation(
            $this->debugString()
        );
    }

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
     * @return void
     */
    public function setMorphTypeField($morphTypeField)
    {
        $this->morphTypeField = $morphTypeField;
    }
}
