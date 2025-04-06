<?php

declare(strict_types=1);

namespace Nip\Records\Mapping;

use Nip\Records\Mapping\Configurator\DataConfigurator;
use Nip\Records\Mapping\MappingManager\CanCache;
use Nip\Records\AbstractModels\RecordManager;
use Nip\Utility\Traits\SingletonTrait;

/**
 * Class MappingManager
 * @package Nip\Records\Mapping
 */
class MappingManager
{
    use SingletonTrait;
    use CanCache;

    protected $booted = false;

    /**
     * @var MappingRepository
     */
    protected $repository;

    /**
     * MappingManager constructor.
     */
    public function __construct()
    {
        $this->repository = new MappingRepository();
    }

    /**
     * @param RecordManager $manager
     * @return MappingData
     */
    public static function for(RecordManager $manager)
    {
        $instance = static::instance();
        $className = $manager->getClassName();
        if (!$instance->repository()->has($className)) {
            static::init($manager);
        }
        return $instance->repository()->get($className);
    }

    /**
     * @param RecordManager $manager
     */
    public static function init(RecordManager $manager)
    {
        $instance = static::instance();
        $data = new MappingData();
        $className = $manager->getClassName();
        DataConfigurator::wire($manager, $data);
        $instance->needsCaching(true);
        $instance->repository()->set($className, $data);
    }

    /**
     * @return MappingRepository
     */
    public function repository(): MappingRepository
    {
        $this->bootIfNeeded();
        return $this->repository;
    }

    protected function bootIfNeeded()
    {
        if ($this->booted === true) {
            return;
        }
        $this->boot();
        $this->booted = true;
    }

    protected function boot()
    {
        $this->initFromCache();
    }
}
