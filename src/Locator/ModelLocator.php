<?php

namespace Nip\Records\Locator;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Locator\Configuration\HasConfigurationTrait;
use Nip\Records\Locator\Exceptions\InvalidModelException;
use Nip\Records\Locator\ModelLocator\Traits\StaticMethodsTrait;
use Nip\Records\Locator\Resolver\Commands\Command;
use Nip\Records\Locator\Resolver\Commands\CommandsFactory;
use Nip\Records\Locator\Resolver\HasResolverPipelineTrait;

/**
 * Class ModelLocator
 * @package Nip\Records\Locator
 */
class ModelLocator
{
    use StaticMethodsTrait;
    use HasResolverPipelineTrait;
    use HasConfigurationTrait;

    /**
     * @param string $alias
     * @return RecordManager
     * @throws InvalidModelException
     */
    public function getManager($alias)
    {
        $manager = $this->locateManager($alias);
        return $manager;
    }

    /**
     * @param $alias
     * @return RecordManager
     * @throws InvalidModelException
     */
    protected function locateManager($alias)
    {
        $command = CommandsFactory::createFromAlias($alias, $this->getConfiguration());
        $pipeline = $this->buildCallPipeline();

        /** @var Command $command */
        $command = $pipeline->process($command);

        if ($command->hasInstance()) {
            return $command->getInstance();
        }

        throw new InvalidModelException(
            "No valid instance located for model alias " .
            "[" . $alias . "]"
        );
    }
}