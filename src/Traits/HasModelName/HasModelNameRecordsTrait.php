<?php

namespace Nip\Records\Traits\HasModelName;

/**
 * Trait HasModelNameRecordsTrait
 * @package Nip\Records\Traits\HasModelName
 */
trait HasModelNameRecordsTrait
{
    /**
     * Model class name
     * @var null|string
     */
    protected $model = null;

    /**
     * @var null|string
     */
    protected $modelNamespacePath = null;

    /**
     * @return string
     */
    public function getModelNamespacePath()
    {
        if ($this->modelNamespacePath == null) {
            $this->initModelNamespacePath();
        }

        return $this->modelNamespacePath;
    }

    public function initModelNamespacePath()
    {
        if ($this->isNamespaced()) {
            $path = $this->generateModelNamespacePathFromClassName() . '\\';
        } else {
            $controller = $this->generateControllerGeneric();
            $path = inflector()->classify($controller) . '\\';
        }
        $this->modelNamespacePath = $path;
    }

    /**
     * @return string
     */
    protected function generateModelNamespacePathFromClassName()
    {
        $className = $this->getClassName();
        $rootNamespace = $this->getRootNamespace();
        $path = str_replace($rootNamespace, '', $className);

        $nsParts = explode('\\', $path);
        array_pop($nsParts);

        return implode('\\', $nsParts);
    }

    /**
     * @return string
     */
    public function getModel()
    {
        if ($this->model == null) {
            $this->inflectModel();
        }

        return $this->model;
    }

    /**
     * @param null $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    protected function inflectModel()
    {
        $class = $this->getClassName();
        $this->model = $this->generateModelClass($class);
    }

    /**
     * @param string $class
     * @return string
     */
    public function generateModelClass($class = null)
    {
        $class = $class ? $class : get_class($this);

        if (strpos($class, '\\')) {
            $nsParts = explode('\\', $class);
            $class = array_pop($nsParts);

            if ($class == 'Table') {
                $class = 'Row';
            } else {
                $class = ucfirst(inflector()->singularize($class));
            }

            return implode('\\', $nsParts) . '\\' . $class;
        }

        return ucfirst(inflector()->singularize($class));
    }
}
