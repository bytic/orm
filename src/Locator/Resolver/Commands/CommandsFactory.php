<?php


namespace Nip\Records\Locator\Resolver\Commands;

use Nip\Records\Locator\Configuration\Configuration;
use Nip\Records\Locator\ModelLocator;
use Nip\Records\Registry\ModelRegistry;

/**
 * Class CommandsFactory
 * @package Nip\Records\Locator\Resolver\Commands
 */
class CommandsFactory
{

    /**
     * @param string $alias
     * @param ModelLocator $modelLocator
     * @return Command
     */
    public static function create($alias, $modelLocator = null)
    {
        $command = new Command();
        $command->setAlias($alias);
        $command->setInstantiator($modelLocator->getInstantiator());
        $command = self::hydrateConfiguration($command, $modelLocator->getConfiguration());
        $command = self::hydrateModelRegistry($command, $modelLocator->getModelRegistry());
        return $command;
    }

    /**
     * @param string $alias
     * @param Configuration|null $configuration
     * @return Command
     */
    public static function createFromAlias($alias, $configuration = null)
    {
        $command = new Command();
        $command->setAlias($alias);
        return self::hydrateConfiguration($command, $configuration);
    }

    /**
     * @param Command $command
     * @param Configuration|null $configuration
     * @return Command
     */
    protected static function hydrateConfiguration(Command $command, $configuration)
    {
        if ($configuration instanceof Configuration) {
            $command->setConfiguration($configuration);
        }
        return $command;
    }

    /**
     * @param Command $command
     * @param ModelRegistry|null $registry
     * @return Command
     */
    protected static function hydrateModelRegistry(Command $command, $registry)
    {
        if ($registry instanceof ModelRegistry) {
            $command->setModelRegistry($registry);
        }
        return $command;
    }
}
