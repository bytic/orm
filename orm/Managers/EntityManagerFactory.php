<?php

declare(strict_types=1);

namespace ByTIC\ORM\Managers;

use ByTIC\ORM\Exception\InvalidArgumentException;
use Nip\Database\Connections\MySqlConnection;

/**
 * Class EntityManagerFactory
 * @package ByTIC\ORM\Managers
 */
class EntityManagerFactory
{
    /**
     * @param array $config
     * @param null $name
     * @return EntityManager
     */
    public function create(array $config = [], $name = null): EntityManager
    {
        $config = $this->parseConfig($config, $name);

        $manager = $this->createManager($config);
        $manager = $this->decorateManager($manager, $config);
        return $manager;
    }

    /**
     * Parse and prepare the configuration.
     *
     * @param array $config
     * @param string $name
     * @return array
     */
    protected function parseConfig($config, $name): array
    {
        if (!isset($config['driver'])) {
            $config['driver'] = 'bytic';
        }
        return $config;
    }

    /**
     * Create a single database connection instance.
     *
     * @param array $config
     * @return EntityManager
     */
    protected function createManager($config)
    {
        $driver = isset($config['driver']) ? $config['driver'] : null;
        switch ($driver) {
            case 'bytic':
                return new ByticEntityManager();
        }

        throw new InvalidArgumentException("Unsupported driver [$driver]");
    }

    /**
     * @param EntityManager $manager
     * @param array $config
     * @return EntityManager
     */
    protected function decorateManager($manager, $config)
    {
        return $manager;
    }
}
