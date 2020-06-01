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
        static::wireController($manager, $data);
        static::wireModel($manager, $data);
        static::wireFields($manager, $data);
        static::wireTableStructure($manager, $data);
        static::wireBootTraits($manager, $data);
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
    protected static function wireController(RecordManager $manager, MappingData $data)
    {
        if (!$data->hasController()) {
            return;
        }
        $manager->setController($data->getController());
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireModel(RecordManager $manager, MappingData $data)
    {
        if (!$data->hasModel()) {
            return;
        }
        $manager->setModel($data->getModel());
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

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireBootTraits(RecordManager $manager, MappingData $data)
    {
        if (!$data->hasBootTraits()) {
            return;
        }
        $manager->setBootTraits($data->getBootTraits());
    }
}
