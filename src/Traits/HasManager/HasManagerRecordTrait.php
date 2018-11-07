<?php

namespace Nip\Records\Traits\HasManager;

use Exception;
use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Locator\ModelLocator;

/**
 * Trait HasManagerRecordTrait
 * @package Nip\Records\Traits\HasManager
 */
trait HasManagerRecordTrait
{
    /**
     * @var string
     */
    protected $managerName = null;

    /**
     * @return RecordManager
     */
    public function getManager()
    {
        return ModelLocator::get($this->getManagerName());
    }

    /**
     * @param RecordManager $manager
     */
    public function setManager($manager)
    {
        $class = get_class($manager);
        ModelLocator::set($class, $manager);
        $this->setManagerName($class);
    }

    /**
     * @return string
     */
    public function getManagerName()
    {
        if ($this->managerName === null) {
            $this->initManagerName();
        }

        return $this->managerName;
    }

    /**
     * @param string $managerName
     */
    public function setManagerName($managerName)
    {
        $this->managerName = $managerName;
    }

    protected function initManagerName()
    {
        $this->setManagerName($this->inflectManagerName());
    }

    /**
     * @return string
     */
    protected function inflectManagerName()
    {
        return ucfirst(inflector()->pluralize($this->getClassName()));
    }

    /**
     * @param string $class
     * @return RecordManager
     * @throws Exception
     */
    protected function getManagerInstance($class)
    {
        if (class_exists($class)) {
            return call_user_func([$class, 'instance']);
        }
        throw new Exception('invalid manager name [' . $class . ']');
    }

}