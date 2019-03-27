<?php

namespace Nip\Records\AbstractModels;

use Nip\Collections\Registry;
use Nip\Database\Query\Insert as InsertQuery;
use Nip\HelperBroker;
use Nip\Records\Collections\Collection as RecordCollection;
use Nip\Records\Traits\ActiveRecord\ActiveRecordsTrait;
use Nip\Records\Traits\HasUrl\HasUrlRecordManagerTrait;
use Nip\Utility\Traits\NameWorksTrait;

/**
 * Class Table
 * @package Nip\Records\_Abstract
 *
 * @method \Nip_Helper_Url Url()
 */
abstract class RecordManager
{
    use NameWorksTrait;
    use ActiveRecordsTrait;
    use HasUrlRecordManagerTrait;

    /**
     * Collection class for current record manager
     *
     * @var string
     */
    protected $collectionClass = null;

    protected $helpers = [];

    /**
     * @var null|string
     */
    protected $urlPK = null;

    /**
     * Model class name
     * @var null|string
     */
    protected $model = null;

    /**
     * @var null|string
     */
    protected $controller = null;

    /**
     * @var null|string
     */
    protected $modelNamespacePath = null;

    protected $registry = null;

    /**
     * Overloads findByRecord, findByField, deleteByRecord, deleteByField, countByRecord, countByField
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     * @example deleteByTitle(array('Lorem ipsum', 'like'))
     * @example countByIdCategory(1)
     *
     * @example findByCategory(Category $item)
     * @example deleteByProduct(Product $item)
     * @example findByIdUser(2)
     */
    public function __call($name, $arguments)
    {
        $return = $this->isCallDatabaseOperation($name, $arguments);
        if ($return !== false) {
            return $return;
        }

        /** @noinspection PhpAssignmentInConditionInspection */
        if ($return = $this->isCallUrl($name, $arguments)) {
            return $return;
        }

        if ($name === ucfirst($name)) {
            return $this->getHelper($name);
        }

        trigger_error("Call to undefined method $name", E_USER_ERROR);

        return $this;
    }

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

        return implode($nsParts, '\\');
    }

    /**
     * @return string
     */
    public function getRootNamespace()
    {
        if (function_exists('app')) {
            return app('app')->getRootNamespace() . 'Models\\';
        }
        return 'App\\Models\\';
    }

    /**
     * @return string
     */
    protected function generateControllerGeneric()
    {
        $class = $this->getClassName();

        return inflector()->unclassify($class);
    }

    /**
     * @param string $name
     * @return \Nip\Helpers\AbstractHelper
     */
    public function getHelper($name)
    {
        return HelperBroker::get($name);
    }

    /**
     * @return string
     */
    public function getModelNamespace()
    {
        return $this->getRootNamespace() . $this->getModelNamespacePath();
    }

    /**
     * @return RecordCollection
     */
    public function newCollection()
    {
        $class = $this->getCollectionClass();
        /** @var RecordCollection $collection */
        $collection = new $class();
        $collection->setManager($this);

        return $collection;
    }

    /**
     * @return string
     */
    public function getCollectionClass()
    {
        if ($this->collectionClass === null) {
            $this->initCollectionClass();
        }

        return $this->collectionClass;
    }

    /**
     * @param string $collectionClass
     */
    public function setCollectionClass($collectionClass)
    {
        $this->collectionClass = $collectionClass;
    }

    protected function initCollectionClass()
    {
        $this->setCollectionClass($this->generateCollectionClass());
    }

    /**
     * @return string
     */
    protected function generateCollectionClass()
    {
        return RecordCollection::class;
    }

    /**
     * @return \Nip\Collections\Registry
     */
    public function getRegistry()
    {
        if (!$this->registry) {
            $this->registry = new Registry();
        }

        return $this->registry;
    }

    /**
     * Factory
     *
     * @param array $data [optional]
     * @return Record
     */
    public function getNew($data = [])
    {
        $pk = $this->getPrimaryKey();
        if (is_string($pk) && isset($data[$pk]) && $this->getRegistry()->has($data[$pk])) {
            $return = $this->getRegistry()->get($data[$pk]);
            $return->writeData($data);
            $return->writeDBData($data);

            return $return;
        }

        $record = $this->getNewRecordFromDB($data);

        return $record;
    }

    /**
     * @param array $data
     * @return Record
     */
    public function getNewRecordFromDB($data = [])
    {
        $record = $this->getNewRecord($data);
        $record->writeDBData($data);

        return $record;
    }

    /**
     * @param array $data
     * @return Record
     */
    public function getNewRecord($data = [])
    {
        $model = $this->getModel();
        /** @var Record $record */
        $record = new $model();
        $record->setManager($this);
        $record->writeData($data);

        return $record;
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

            return implode($nsParts, '\\') . '\\' . $class;
        }

        return ucfirst(inflector()->singularize($class));
    }

    /**
     * @return \Nip\Request
     */
    public function getRequest()
    {
        return request();
    }

    public function __wakeup()
    {
        $this->initDB();
    }

    /**
     * @return RecordCollection
     */
    public function getAll()
    {
        if (!$this->getRegistry()->has("all")) {
            $this->getRegistry()->set("all", $this->findAll());
        }

        return $this->getRegistry()->get("all");
    }

    /**
     * @param Record $model
     * @return array
     */
    public function getQueryModelData($model)
    {
        $data = [];

        $fields = $this->getFields();
        foreach ($fields as $field) {
            if (isset($model->{$field})) {
                $data[$field] = $model->{$field};
            }
        }

        return $data;
    }

    /**
     * The name of the field used as a foreign key in other tables
     * @return string
     */
    public function getUrlPK()
    {
        if ($this->urlPK == null) {
            $this->urlPK = $this->getPrimaryKey();
        }

        return $this->urlPK;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasField($name)
    {
        $fields = $this->getFields();
        if (is_array($fields) && in_array($name, $fields)) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function getFullTextFields()
    {
        $return = [];
        $structure = $this->getTableStructure();
        foreach ($structure['indexes'] as $name => $index) {
            if ($index['fulltext']) {
                $return[$name] = $index['fields'];
            }
        }

        return $return;
    }

    /**
     * Sets model and database table from the class name
     */
    protected function inflect()
    {
        $this->initController();
    }
}
