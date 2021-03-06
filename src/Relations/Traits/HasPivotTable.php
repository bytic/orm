<?php

namespace Nip\Records\Relations\Traits;

use Nip\Database\Connections\Connection;
use Nip\Database\Query\Select as SelectQuery;

/**
 * Trait HasPivotTable
 * @package Nip\Records\Relations\Traits
 */
trait HasPivotTable
{
    use HasPivotForeignKey;
    use HasPivotPrimaryKey;

    /**
     * @param $params
     */
    public function addPivotParams($params)
    {
        $this->checkParamPivotFk($params);
        $this->checkParamPivotPrimaryKey($params);
    }

    /**
     * @return Connection
     */
    public function getDB()
    {
        return $this->getParam("link-db") == 'with' ? $this->getWith()->getDB() : parent::getDB();
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return string
     */
    public function generateTable()
    {
        return $this->generatePivotTable();
    }

    /**
     * Builds the name of a has-and-belongs-to-many association table
     * @return string
     */
    public function generatePivotTable()
    {
        $tables = [
            $this->getManager()->getTable(),
            $this->getWith()->getTable()
        ];
        sort($tables);

        return implode("_", $tables);
    }

    /**
     * @param SelectQuery $query
     */
    protected function hydrateQueryWithPivotConstraints($query)
    {
        $pk = $this->getPivotPrimaryKey();
        $fk = $this->getPivotFK();
        $query->where("`{$this->getTable()}`.`$fk` = `{$this->getWith()->getTable()}`.`$pk`");
    }
}
