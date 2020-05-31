<?php

namespace Nip\Records\Mapping\Configurator;

use Nip\Records\Mapping\MappingData;
use Nip\Records\RecordManager;

/**
 * Class EntityConfigurator
 * @package Nip\Records\Mapping\Configurator
 */
class EntityConfigurator
{
    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    public static function wire(RecordManager $manager, MappingData $data)
    {
        static::wireTable($manager, $data);
        static::wireFields($manager, $data);
        static::wireTableStructure($manager, $data);
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireTable(RecordManager $manager, MappingData $data)
    {
        if (!$data->hasTable()) {
            return;
        }
        $manager->setTable($data->getTable());
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireFields(RecordManager $manager, MappingData $data)
    {
        if (!$data->hasFields()) {
            return;
        }
        $manager->setFields($data->getFields());
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireTableStructure(RecordManager $manager, MappingData $data)
    {
        if (!$data->hasTableStructure()) {
            return;
        }
        $manager->setTableStructure($data->getTableStructure());
    }
}
