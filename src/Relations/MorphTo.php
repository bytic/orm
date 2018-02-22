<?php

namespace Nip\Records\Relations;

use Exception;
use MongoDB\Driver\Query;
use Nip\Records\AbstractModels\Record;
use Nip\Records\Collections\Collection;
use Nip\Records\RecordManager;
use Nip\Records\Relations\Exceptions\ModelNotLoadedInRelation;
use Nip\Records\Relations\Traits\HasMorphTypeTrait;
use Nip\HelperBroker;
use Nip_Helper_Arrays as ArraysHelper;

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
     * @throws Exception
     */
    public function addParams($params)
    {
        $this->checkParamMorphPrefix($params);
        parent::addParams($params);
    }

    /**
     * @inheritdoc
     */
    public function getEagerResults($collection)
    {
        if ($collection->count() < 1) {
            if ($this->getItem() instanceof Record) {
                return $this->getWith()->newCollection();
            } else {
                return new Collection();
            }
        }
        $types = $this->getTypesFromCollection($collection);
        $results = new Collection();
        foreach ($types as $type) {
            $manager = $this->getModelManagerInstance($type);
            $query = $this->getEagerQueryType($collection, $manager);
            $typeCollection = $manager->findByQuery($query);
            foreach ($typeCollection as $item) {
                $results->add($item);
            }
        }

        return $results;
    }

    /**
     * @param Collection $collection
     * @param $manager
     * @return Query
     * @throws Exception
     */
    public function getEagerQueryType(Collection $collection, $manager)
    {
        $fkList = $this->getEagerFkListType($collection, $manager);
        $query = $manager->newQuery();
        $query->where($manager->getPrimaryKey() . ' IN ?', $fkList);
        return $query;
    }

    /**
     * @param Collection $collection
     * @param RecordManager $manager
     * @return array
     */
    public function getEagerFkListType(Collection $collection, $manager)
    {
        $foreignKey = $this->getFK();
        $typeField = $this->getMorphTypeField();
        $type = $manager->getMorphName();
        $return = [];

        foreach ($collection as $item) {
            if ($item->{$typeField} == $type) {
                $return[] = $item->{$foreignKey};
            }
        }

        return array_unique($return);
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @param Record $record
     * @return array
     * @throws \Exception
     */
    protected function getDictionaryKey(Record $record)
    {
        $key = $record->getManager()->getMorphName();
        $key .= '-' . $record->getPrimaryKey();

        return $key;
    }

    /**
     * @param $dictionary
     * @param $collection
     * @param $record
     * @return mixed
     */
    public function getResultsFromCollectionDictionary($dictionary, $collection, $record)
    {
        $dictionaryKey = $record->{$this->getMorphTypeField()};
        $dictionaryKey .= '-' . $record->{$this->getFK()};
        if (isset($dictionary[$dictionaryKey])) {
            return $dictionary[$dictionaryKey];
        }

        return null;
    }

    /**
     * @param Query $query
     * @param array $fkList
     * @return Query
     * @throws Exception
     */
    protected function populateEagerQueryFromFkList($query, $fkList)
    {

        return $query;
    }

    /**
     * @param $collection
     * @return array
     */
    public function getTypesFromCollection($collection)
    {
        $type = $this->getMorphTypeField();

        /** @var ArraysHelper $arrayHelper */
        $arrayHelper = HelperBroker::get('Arrays');
        return $arrayHelper->pluck($collection, $type);
    }
}
