<?php

namespace Nip\Records\Traits\TableStructure;

/**
 * Trait TableStructureRecordsTrait
 * @package Nip\Records\Traits\TableStructure
 */
trait TableStructureRecordsTrait
{
    protected $tableStructure = null;

    /**
     * @var null|array
     */
    protected $fields = null;

    /**
     * @return null
     */
    public function getFields()
    {
        $this->checkFieldsIsInitiated();

        return $this->fields;
    }

    /**
     * @param array|null $fields
     */
    public function setFields(?array $fields): void
    {
        $this->fields = $fields;
    }

    /**
     * @param $name
     */
    protected function isNullable($name): bool
    {
        $structure = $this->getTableStructure();
        if (!isset($structure['fields'][$name]['nullable'])) {
            return false;
        }
        return (bool) $structure['fields'][$name]['nullable'];
    }

    protected function checkFieldsIsInitiated()
    {
        if ($this->fields === null) {
            $this->initFields();
        }
    }

    public function initFields()
    {
        $structure = $this->getTableStructure();
        $this->setFields(array_keys($structure['fields']));
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasField(string $name)
    {
        $this->checkFieldsIsInitiated();
        return isset($this->fields[$name]);
    }

    /**
     * @return mixed
     */
    public function getTableStructure()
    {
        if ($this->tableStructure == null) {
            $this->initTableStructure();
        }

        return $this->tableStructure;
    }

    /**
     * @param null $tableStructure
     */
    public function setTableStructure($tableStructure)
    {
        $this->tableStructure = $tableStructure;
    }

    protected function initTableStructure()
    {
        $this->setTableStructure($this->generateTableStructure());
    }

    /**
     * @return mixed
     */
    protected function generateTableStructure()
    {
        return $this->getDB()->getMetadata()->describeTable($this->getTable());
    }
}
