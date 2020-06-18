<?php

namespace Nip\Records\AbstractModels;

use Nip\Collections\Registry;
use Nip\Database\Query\Insert as InsertQuery;
use Nip\HelperBroker;
use Nip\Records\Collections\Collection as RecordCollection;
use Nip\Records\EventManager\HasEvents;
use Nip\Records\Legacy\AbstractModels\RecordManagerLegacyTrait;
use Nip\Records\Traits\ActiveRecord\ActiveRecordsTrait;
use Nip\Records\Traits\CanBoot\CanBootRecordsTrait;
use Nip\Records\Traits\CanBootTraits\CanBootTraitsRecordsTrait;
use Nip\Records\Traits\HasController\HasControllerRecordsTrait;
use Nip\Records\Traits\HasModelName\HasModelNameRecordsTrait;
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
    use CanBootRecordsTrait;
    use CanBootTraitsRecordsTrait;
    use HasControllerRecordsTrait;
    use HasModelNameRecordsTrait;
    use HasEvents;
    use HasUrlRecordManagerTrait;

    use RecordManagerLegacyTrait;

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

    protected $registry = null;

    public function __construct()
    {
        $this->bootIfNotBooted();
    }

    /**
     * Overloads findByRecord, findByField, deleteByRecord, deleteByField, countByRecord, countByField
     *
     * @example findByCategory(Category $item)
     * @example deleteByProduct(Product $item)
     * @example findByIdUser(2)
     * @example deleteByTitle(array('Lorem ipsum', 'like'))
     * @example countByIdCategory(1)
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
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
     * When a model is being unserialized, check if it needs to be booted.
     *
     * @return void
     */
    public function __wakeup()
    {
        $this->initDB();
        $this->bootIfNotBooted();
    }

    /**
     * @return string
     */
    public function getRootNamespace()
    {
        if (function_exists('app') && app()->has('app')) {
            return app('app')->getRootNamespace() . 'Models\\';
        }
        return 'App\\Models\\';
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
