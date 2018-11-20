<?php

namespace Nip\Records\Traits\TableStructure;

/**
 * Trait TableStructureRecordsTrait
 * @package Nip\Records\Traits\TableStructure
 */
trait TableStructureRecordsTrait
{
    protected $tableStructure = null;

    protected $fields = null;

    /**
     * @return null
     */
    public function getFields()
    {
        if ($this->fields === null) {
            $this->initFields();
        }

        return $this->fields;
    }

    public function initFields()
    {
        $structure = $this->getTableStructure();
        $this->fields = array_keys($structure['fields']);
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
