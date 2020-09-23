<?php

namespace Nip\Records\Locator\Resolver\Pipeline\Stages;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Locator\Resolver\Commands\Command;

/**
 * Class AbstractStage
 * @package Nip\Records\Locator\Resolver\Pipeline\Stages
 */
abstract class AbstractStage implements StageInterface
{
    /**
     * @var Command
     */
    protected $command;

    /**
     * @param Command $methodCall
     * @return Command
     */
    public function __invoke(Command $command): Command
    {
        $this->setCommand($command);
        $this->processCommand();
        return $command;
    }

    /**
     * @return void
     */
    abstract public function processCommand();

    /**
     * @param $class
     * @return bool
     */
    protected function isValidClassName($class)
    {
        $this->getCommand()->addTry($class);
        return class_exists($class);
    }

    /**
     * @param $class
     * @return RecordManager
     */
    protected function newModelManager($class)
    {
        return $this->getCommand()->getInstantiator()->instantiate($class);
    }

    /**
     * @return Command
     */
    public function getCommand(): Command
    {
        return $this->command;
    }

    /**
     * @param Command $command
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;
    }
}
