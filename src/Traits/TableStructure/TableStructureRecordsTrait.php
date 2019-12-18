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

    protected function checkFieldsIsInitiated()
    {
        if ($this->fields === null) {
            $this->initFields();
        }
    }

    public function initFields()
    {
        $structure = $this->getTableStructure();
        $this->fields = array_keys($structure['fields']);
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
    protected function getTableStructure()
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
        $this->setTableStructure($this->getDB()->getMetadata()->describeTable($this->getTable()));
    }
}
