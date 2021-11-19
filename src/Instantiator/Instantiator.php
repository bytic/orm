<?php

namespace Nip\Records\Instantiator;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Mapping\Configurator\EntityConfigurator;
use Nip\Records\Mapping\MappingManager;
use Nip\Records\Registry\HasModelRegistry;
use Nip\Utility\Oop;

/**
 * Class Instantiator
 * @package Nip\Records\Instantiator
 */
class Instantiator
{
    use HasModelRegistry;

    /**
     * @param $className
     * @return mixed
     */
    public function instantiate($className)
    {
        $registry = $this->getModelRegistry();
        if ($registry->has($className)) {
            return $registry->get($className);
        }

        $manager = $this->create($className);
        $registry->set($manager->getClassName(), $manager);
        return $this->prepare($manager);
    }

    /**
     * @param $className
     * @return mixed
     */
    protected function create($className)
    {
        if (false === Oop::classUsesTrait($className, CanInstanceTrait::class)) {
            return call_user_func([$className, "instance"]);
        }

        return new $className();
    }

    /**
     * @param RecordManager $manager
     * @return RecordManager
     */
    protected function prepare(RecordManager $manager)
    {
        $manager->bootIfNotBooted();
        $mapping = MappingManager::for($manager);
        EntityConfigurator::wire($manager, $mapping);
        return $manager;
    }
}
