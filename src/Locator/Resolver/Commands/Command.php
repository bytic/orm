<?php


namespace Nip\Records\Locator\Resolver\Commands;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Instantiator\HasInstantiatorTrait;
use Nip\Records\Locator\Configuration\Configuration;
use Nip\Records\Registry\HasModelRegistry;
use Nip\Records\Registry\ModelRegistry;

/**
 * Class Command
 * @package Nip\Records\Locator\Resolver\Commands
 */
class Command
{
    use HasInstantiatorTrait;
    use HasModelRegistry;

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var []
     */
    protected $tries = [];

    /**
     * @var RecordManager
     */
    protected $instance = null;

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return bool
     */
    public function hasConfiguration()
    {
        return $this->getConfiguration() instanceof Configuration;
    }

    /**
     * @return Configuration
     */
    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return bool
     */
    public function hasInstance()
    {
        return $this->getInstance() instanceof RecordManager;
    }

    /**
     * @return RecordManager|null
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param RecordManager $instance
     */
    public function setInstance(RecordManager $instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return bool
     */
    public function hasNamespaces()
    {
        return $this->getConfiguration()->hasNamespaces();
    }

    /**
     * @return array
     */
    public function getNamespaces()
    {
        return $this->hasNamespaces() ? $this->getConfiguration()->getNamespaces() : [];
    }

    /**
     * @return ModelRegistry
     */
    public function getTries(): array
    {
        return $this->tries;
    }

    /**
     * @param $class
     */
    public function addTry($class)
    {
        $this->tries[] = $class;
    }
}
