<?php

namespace Nip\Records\Locator\Resolver\Pipeline\Stages;

use Nip\Records\AbstractModels\RecordManager;

/**
 * Class ClassInstance
 * @package Nip\Records\Locator\Resolver\Pipeline\Stages
 */
class RegistryInstance extends AbstractStage
{
    /**
     * @return void
     * @throws \Exception
     */
    public function processCommand()
    {
        $registry = $this->getCommand()->getModelRegistry();
        $alias = $this->getCommand()->getAlias();
        if ($registry->has($alias)) {
            $manager = $registry->get($alias);
            $this->getCommand()->setInstance($manager);
        }
    }
}
