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
        if (!$this->hasManagerName()) {
            $manager = ModelLocator::for($this);
            $this->setManager($manager);
            return $manager;
        }
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
        if ($this->hasManagerName() === false) {
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

    /**
     * @return bool
     */
    public function hasManagerName()
    {
        return is_string($this->managerName);
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
}
