<?php

declare(strict_types=1);

namespace ByTIC\ORM\ORM;

use ByTIC\ORM\Exception\InvalidArgumentException;

/**
 * Trait HasConnections
 * @package ByTIC\ORM\ORM
 */
trait HasConnections
{
    /**
     * @var string
     */
    protected $defaultConnection = 'default';

    /**
     * @var array
     */
    protected $connections = [];

    public function getDefaultConnectionName()
    {
        if (isset($this->connections[$this->defaultConnection])) {
            return $this->defaultConnection;
        }

        return head($this->connections);
    }

    public function getConnection($name = null)
    {
        $name = $name ?: $this->getDefaultConnectionName();

        if (!$this->connectionExists($name)) {
            throw new InvalidArgumentException(sprintf('Doctrine Connection named "%s" does not exist.', $name));
        }

//        if (isset($this->connectionsMap[$name])) {
//            return $this->connectionsMap[$name];
//        }
//
//        return $this->connectionsMap[$name] = $this->getService(
//            $this->getConnectionBindingName($this->connections[$name])
//        );
    }

    public function getConnections()
    {
        $connections = [];
        foreach ($this->getConnectionNames() as $name) {
            $connections[$name] = $this->getConnection($name);
        }

        return $connections;
    }

    public function getConnectionNames()
    {
        return $this->connections;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function connectionExists($name)
    {
        return isset($this->connections[$name]);
    }
}