<?php

namespace Nip\Records\Traits\HasController;

use ByTIC\Namefy\Namefy;

/**
 * Trait HasControllerRecordsTrait
 * @package Nip\Records\Traits\HasController
 */
trait HasControllerRecordsTrait
{
    use HasControllerRecordsLegacyTrait;

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
        $this->setController($this->generateController());
    }

    /**
     * @return mixed
     */
    protected function generateController()
    {
        $model = $this->getClassName();
        if ($this->isNamespaced()) {
            $model = str_replace($this->getRootNamespace(), '', $model);
        }
        return Namefy::repository($model)->controller();
    }
}
