<?php

declare(strict_types=1);

namespace ByTIC\ORM\ORM;

use ByTIC\ORM\Exception\InvalidArgumentException;
use ByTIC\ORM\Exception\ORMException;
use ByTIC\ORM\Managers\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Trait HasManagers
 * @package ByTIC\ORM\ORM
 */
trait HasManagers
{
    /**
     * @var string
     */
    protected $defaultManager = 'default';
    /**
     * @var array
     */
    protected $managers = [];

    /**
     * @var array
     */
    protected $managersMap = [];

    /**
     * @inheritDoc
     */
    public function getDefaultManagerName(): string
    {
        if (isset($this->managers[$this->defaultManager])) {
            return $this->defaultManager;
        }

        return head($this->managers);
    }

    /**
     * @inheritDoc
     */
    public function getManager($name = null): EntityManagerInterface
    {
        $name = $name ?: $this->getDefaultManagerName();

        if (!$this->managerExists($name)) {
            throw new InvalidArgumentException(sprintf('Doctrine Manager named "%s" does not exist.', $name));
        }

        if (isset($this->managersMap[$name])) {
            return $this->managersMap[$name];
        }

        return $this->managersMap[$name] = $this->getService(
            $this->getManagerBindingName($this->managers[$name])
        );
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function managerExists($name)
    {
        return isset($this->managers[$name]);
    }

    /**
     * @return EntityManagerInterface[]
     */
    public function getManagers(): array
    {
        $managers = [];
        foreach ($this->getManagerNames() as $name) {
            $managers[$name] = $this->getManager($name);
        }

        return $managers;
    }

    /**
     * Resets a named object manager.
     *
     * This method is useful when an object manager has been closed
     * because of a rollbacked transaction AND when you think that
     * it makes sense to get a new one to replace the closed one.
     *
     * Be warned that you will get a brand new object manager as
     * the existing one is not useable anymore. This means that any
     * other object with a dependency on this object manager will
     * hold an obsolete reference. You can inject the registry instead
     * to avoid this problem.
     *
     * @param string|null $name The object manager name (null for the default one).
     *
     * @return ObjectManager
     */
    public function resetManager($name = null)
    {
        $this->closeManager($name);

        return $this->getManager($name);
    }

    /**
     * @inheritDoc
     */
    public function getAliasNamespace($alias)
    {
        foreach ($this->getManagerNames() as $name) {
            try {
                return $this->getManager($name)->getConfiguration()->getEntityNamespace($alias);
            } catch (ORMException $e) {
            }
        }

        throw ORMException::unknownEntityNamespace($alias);
    }

    /**
     * @inheritDoc
     */
    public function getManagerNames(): array
    {
        return $this->managers;
    }

    /**
     * @inheritDoc
     */
    public function getManagerForClass($class): EntityManagerInterface
    {
        // Check for namespace alias
        if (strpos($class, ':') !== false) {
            list($namespaceAlias, $simpleClassName) = explode(':', $class, 2);
            $class = $this->getAliasNamespace($namespaceAlias) . '\\' . $simpleClassName;
        }

        $managers = $this->getManagers();

        if (count($managers) === 1) {
            return reset($managers);
        }
        foreach ($managers as $manager) {
            if ($manager->hasRepository($class)) {
                return $manager;
            }
        }
        return $this->getManager();
    }
}