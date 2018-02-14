<?php


namespace Nip\Records\Locator\Resolver\Commands;

use Nip\Records\Locator\Configuration\Configuration;

/**
 * Class CommandsFactory
 * @package Nip\Records\Locator\Resolver\Commands
 */
class CommandsFactory
{
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
}
