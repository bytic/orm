<?php

namespace Nip\Records\Filters\Column\Traits;

use Nip\Records\Filters\AbstractFilter;
use Nip\Records\Filters\FilterManager;

/**
 * Trait HasDbNameTrait
 * @package Nip\Records\Filters\Column\Traits
 *
 * @method FilterManager getManager()
 * @method string getField()
 */
trait HasDbNameTrait
{
    protected $dbName = null;

    /**
     * @return string
     */
    public function getDbName()
    {
        if ($this->dbName == null) {
            $this->initDbName();
        }

        return $this->dbName;
    }

    protected function initDbName()
    {
        $table = $this->getManager()->getRecordManager()->getTable();
        $this->dbName = '`' . $table . '`.`' . $this->getField() . '`';
    }

    /**
     * @param mixed $dbName
     * @return HasDbNameTrait|AbstractFilter
     */
    public function setDbName($dbName)
    {
        $this->dbName = $dbName;

        return $this;
    }
}
