<?php

namespace Nip\Records\Instantiator;

use Nip\Records\Mapping\Configurator\EntityConfigurator;
use Nip\Records\Mapping\MappingManager;
use Nip\Records\RecordManager;
use Nip\Records\Registry\HasModelRegistry;

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
        return $manager;
    }

    /**
     * @param $className
     * @return mixed
     */
    protected function create($className)
    {
        if (method_exists($className, "instance")) {
            $manager = call_user_func([$className, "instance"]);
        } else {
            $manager = new $className();
        }

        return $this->prepare($manager);
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
