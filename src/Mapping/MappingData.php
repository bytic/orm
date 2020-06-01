<?php

namespace Nip\Records\Mapping;

/**
 * Class MappingData
 * @package Nip\Records\Mapping
 */
class MappingData implements \Serializable
{
    protected $table;
    protected $controller;
    protected $model;
    protected $tableStructure;
    protected $fields;
    protected $bootTraits;

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return bool
     */
    public function hasTable(): bool
    {
        return is_string($this->table);
    }

    /**
     * @param mixed $table
     */
    public function setTable($table): void
    {
        $this->table = $table;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller): void
    {
        $this->controller = $controller;
    }

    public function hasController(): bool
    {
        return !empty($this->controller);
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    public function hasModel(): bool
    {
        return !empty($this->model);
    }


    /**
     * @return mixed
     */
    public function getTableStructure()
    {
        return $this->tableStructure;
    }

    /**
     * @return bool
     */
    public function hasTableStructure(): bool
    {
        return is_array($this->tableStructure);
    }

    /**
     * @param mixed $tableStructure
     */
    public function setTableStructure($tableStructure): void
    {
        $this->tableStructure = $tableStructure;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return bool
     */
    public function hasFields(): bool
    {
        return is_array($this->fields);
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @return mixed
     */
    public function getBootTraits()
    {
        return $this->bootTraits;
    }

    /**
     * @param mixed $bootTraits
     */
    public function setBootTraits($bootTraits): void
    {
        $this->bootTraits = $bootTraits;
    }

    public function hasBootTraits(): bool
    {
        return is_array($this->bootTraits);
    }


    /**
     * @inheritDoc
     */
    public function serialize()
    {
        $data = [];
        $properties = ['table', 'controller', 'tableStructure', 'fields', 'bootTraits'];
        foreach ($properties as $property) {
            $data[$property] = $this->{$property};
        }
        return serialize($data);
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->fromArray($data);
    }

    /**
     * @param $data
     */
    public function fromArray($data)
    {
        foreach ($data as $key=>$value) {
            if (property_exists($this, $value)) {
                $this->{$key} = $value;
            }
        }
    }

}