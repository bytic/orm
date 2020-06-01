<?php

namespace Nip\Records\Traits\HasController;

/**
 * Trait HasControllerRecordsTrait
 * @package Nip\Records\Traits\HasController
 */
trait HasControllerRecordsTrait
{

    /**
     * @var null|string
     */
    protected $controller = null;
    /**
     * @return string
     */
    public function getController()
    {
        if ($this->controller === null) {
            $this->initController();
        }

        return $this->controller;
    }

    /**
     * @param null|string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    protected function initController()
    {
        if ($this->isNamespaced()) {
            $controller = $this->generateControllerNamespaced();
        } else {
            $controller = $this->generateControllerGeneric();
        }
        $this->setController($controller);
    }

    /**
     * @return string
     */
    protected function generateControllerNamespaced()
    {
        $class = $this->getModelNamespacePath();
        $class = trim($class, '\\');

        return inflector()->unclassify($class);
    }

    /**
     * @return string
     */
    protected function generateControllerGeneric()
    {
        $class = $this->getClassName();

        return inflector()->unclassify($class);
    }

}
