<?php

namespace Nip\Records\Traits\Unique;

use Nip\Database\Query\Condition\Condition;
use Nip\Records\AbstractModels\Record;

/**
 * Class RecordsTrait
 * @package Nip\Records\Traits\Unique
 */
trait RecordsTrait
{
    protected $uniqueFields = null;

    /**
     * @param Record $item
     * @return bool|false|Record
     */
    public function exists(Record $item)
    {
        $params = $this->generateExistsParams($item);

        if (!$params) {
            return false;
        }
        return $this->findOneByParams($params);
    }

    /**
     * @param Record $item
     * @return array|bool
     */
    public function generateExistsParams(Record $item)
    {
        $params = [];
        $params['where'] = [];

        $uniqueFields = $this->getUniqueFields();

        if (!$uniqueFields) {
            return false;
        }

        foreach ($uniqueFields as $uniqueName => $fields) {
            $conditions = [];
            foreach ($fields as $field) {
                $conditions[] = "`$field` = '{$item->{$field}}'";
            }
            $params['where'][$uniqueName . '-UNQ'] = implode(' AND ', $conditions);
        }

        $pk = $this->getPrimaryKey();
        if ($item->getPrimaryKey()) {
            $params['where'][] = ["$pk != ?", $item->getPrimaryKey()];
        }
        return $params;
    }

    /**
     * @return null
     */
    public function getUniqueFields()
    {
        if ($this->uniqueFields === null) {
            $this->initUniqueFields();
        }

        return $this->uniqueFields;
    }

    /**
     * @return array|null
     */
    public function initUniqueFields()
    {
        $this->uniqueFields = [];
        $structure = $this->getTableStructure();
        foreach ($structure['indexes'] as $name => $index) {
            if ($index['unique'] && $name != 'PRIMARY') {
                $this->uniqueFields[$name] = $index['fields'];
            }
        }

        return $this->uniqueFields;
    }
}
