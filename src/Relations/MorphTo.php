<?php

namespace Nip\Records\Relations;

use Nip\Records\AbstractModels\Record;
use Nip\Records\Relations\Exceptions\ModelNotLoadedInRelation;
use Nip\Records\Relations\Traits\HasMorphTypeTrait;

/**
 * Class MorphToMany
 * @package Nip\Records\Relations
 */
class MorphTo extends BelongsTo
{
    use HasMorphTypeTrait;

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

}
