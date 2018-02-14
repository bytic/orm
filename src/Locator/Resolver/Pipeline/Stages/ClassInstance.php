<?php

namespace Nip\Records\Locator\Resolver\Pipeline\Stages;

use Nip\Records\AbstractModels\RecordManager;

/**
 * Class ClassInstance
 * @package Nip\Records\Locator\Resolver\Pipeline\Stages
 */
class ClassInstance extends AbstractStage
{
    /**
     * @return void
     * @throws \Exception
     */
    public function processCommand()
    {
        if ($this->isValidClassName($this->getCommand()->getAlias())) {
            $manager = $this->newModelManager($this->getCommand()->getAlias());
            $this->getCommand()->setInstance($manager);
        }
    }
}
