<?php

namespace Nip\Records\Mapping\Configurator;

use Nip\Records\Mapping\MappingData;
use Nip\Records\RecordManager;

/**
 * Class DataConfigurator
 * @package Nip\Records\Mapping\Configurator
 */
class DataConfigurator
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
        $data->setTable($manager->getTable());
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireController(RecordManager $manager, MappingData $data)
    {
        $data->setController($manager->getController());
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireModel(RecordManager $manager, MappingData $data)
    {
        $data->setModel($manager->getModel());
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireFields(RecordManager $manager, MappingData $data)
    {
        $data->setFields($manager->getFields());
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireTableStructure(RecordManager $manager, MappingData $data)
    {
        $data->setTableStructure($manager->getTableStructure());
    }

    /**
     * @param RecordManager $manager
     * @param MappingData $data
     */
    protected static function wireBootTraits(RecordManager $manager, MappingData $data)
    {
        $data->setBootTraits($manager->getBootTraits());
    }
}
