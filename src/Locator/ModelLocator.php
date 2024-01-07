<?php

namespace Nip\Records\Locator;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Instantiator\HasInstantiatorTrait;
use Nip\Records\Locator\Configuration\HasConfigurationTrait;
use Nip\Records\Locator\Exceptions\InvalidModelException;
use Nip\Records\Locator\Resolver\Commands\Command;
use Nip\Records\Locator\Resolver\Commands\CommandsFactory;
use Nip\Records\Locator\Resolver\HasResolverPipelineTrait;
use Nip\Records\Registry\HasModelRegistry;

/**
 * Class ModelLocator
 * @package Nip\Records\Locator
 */
class ModelLocator
{
    use ModelLocator\Traits\StaticMethodsTrait;
    use HasResolverPipelineTrait;
    use HasConfigurationTrait;
    use HasModelRegistry;
    use HasInstantiatorTrait;

    /**
     * @param string $alias
     * @return RecordManager
     * @throws InvalidModelException
     */
    public function getManager($alias, $default = null): RecordManager
    {
        if ($alias instanceof \Closure) {
            return $this->locateManager($alias(), $default);
        }
        return $this->locateManager($alias, $default);
    }

    public function getManagerClass($alias): string
    {
        return get_class($this->getManager($alias));
    }

    /**
     * @param $alias
     * @return RecordManager
     * @throws InvalidModelException
     */
    protected function locateManager($alias, $default = null)
    {
        $registry = $this->getModelRegistry();
        if ($registry->has($alias)) {
            return $registry->get($alias);
        }

        $manager = $this->locateManagerPipeline($alias, $default);
        $registry->set($alias, $manager);
        $registry->set($manager->getClassName(), $manager);
        return $manager;
    }

    /**
     * @param $alias
     * @return RecordManager
     * @throws InvalidModelException
     */
    protected function locateManagerPipeline($alias, $default = null)
    {
        $command = CommandsFactory::create($alias, $this);
        $command->setDefault($default);

        $pipeline = $this->buildCallPipeline();

        /** @var Command $command */
        $command = $pipeline->process($command);

        if ($command->hasInstance()) {
            return $command->getInstance();
        }

        throw new InvalidModelException(
            "No valid instance located for model alias "
            . "[" . $alias . "]"
            . " Tried [" . implode(', ', $command->getTries()) . "]"
        );
    }
}
