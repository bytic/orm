<?php

namespace Nip\Records\Relations\Traits;

use Exception;
use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Locator\Exceptions\InvalidModelException;
use Nip\Records\Traits\Relations\HasRelationsRecordsTrait;

/**
 * Trait HasWithTrait
 * @package Nip\Records\Relations\Traits
 */
trait HasWithTrait
{
    /**
     * @var RecordManager
     */
    protected $with = null;

    /**
     * @var null|string
     */
    protected $withPK = null;

    /** @noinspection PhpDocMissingThrowsInspection
     * @return RecordManager
     */
    public function getWith()
    {
        if ($this->with == null) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $this->initWith();
        }

        return $this->with;
    }

    /**
     * @param RecordManager|HasRelationsRecordsTrait $object
     * @return $this
     */
    public function setWith($object)
    {
        $this->with = $object;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function initWith()
    {
        $className = $this->getWithClass();
        $this->setWithClass($className);
    }

    /** @noinspection PhpDocMissingThrowsInspection
     * @return string
     */
    public function getWithClass()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return inflector()->pluralize($this->getName());
    }

    /**
     * @param string $name
     */
    public function setClass($name)
    {
        $this->setWithClass($name);
    }

    /** @noinspection PhpDocMissingThrowsInspection
     * @param string $name
     */
    public function setWithClass($name)
    {
        try {
            $manager = $this->getModelManagerInstance($name);
            $this->setWith($manager);
        } catch (InvalidModelException $exception) {
            /** @noinspection PhpUnhandledExceptionInspection */
            throw new InvalidModelException(
                'Cannot instance records [' . $name . '] in ' . $this->debugString()
                . '|| with message ' . $exception->getMessage()
            );
        }
    }

    /**
     * @return string
     */
    public function getWithPK()
    {
        if ($this->withPK === null) {
            $this->initWithPK();
        }
        return $this->withPK;
    }

    /**
     * @param string|null $withPK
     */
    public function setWithPK(string $withPK)
    {
        $this->withPK = $withPK;
    }

    protected function initWithPK()
    {
        $this->withPK = $this->generateWithPK();
    }

    /**
     * @return string
     */
    protected function generateWithPK()
    {
        return $this->getWith()->getPrimaryKey();
    }
}
