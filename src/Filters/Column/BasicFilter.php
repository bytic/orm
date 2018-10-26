<?php

namespace Nip\Records\Filters\Column;

use Nip\Database\Query\Select as SelectQuery;

/**
 * Class BasicFilter
 * @package Nip\Records\Filters\Column
 */
class BasicFilter extends AbstractFilter implements FilterInterface
{

    /**
     * @var string
     */
    protected $databaseOperation = '=';

    /**
     * @param SelectQuery $query
     */
    public function filterQuery($query)
    {
        $value = $this->getValue();
        if ($this->getDatabaseOperation() == 'LIKE%%') {
            $query->where("{$this->getDbName()} LIKE ?", "%{$value}%");
        } elseif (is_array($value)) {
            $query->where("{$this->getDbName()} IN ?", $value);
        } else {
            $query->where("{$this->getDbName()} = ?", $this->getValue());
        }
    }


    /**
     * @return string
     */
    public function getDatabaseOperation()
    {
        return $this->databaseOperation;
    }

    /**
     * @param string $databaseOperation
     */
    public function setDatabaseOperation($databaseOperation)
    {
        $this->databaseOperation = $databaseOperation;
    }
}
