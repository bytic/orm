<?php

namespace Nip\Records\Traits\Unique;

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

        $where = $params['where'];
        $uniqueWhere = [];
        foreach ($where as $key => $value) {
            if (strpos($key, 'UNQ') !== false) {
                $uniqueWhere[] = $value;
                unset($params['where'][$key]);
            }
        }

        $params['where'][] = implode(' OR ', $uniqueWhere);

        return $this->findOneByParams($params);
    }

    /**
     * @param Record $item
     * @return array|bool
     */
    public function generateExistsParams(Record $item)
    {
        $conditions = $this->generateUniqueConditions($item);

        if (count($conditions) < 1) {
            return false;
        }

        $params = [];
        $params['where'] = $conditions;

        $pk = $this->getPrimaryKey();
        if ($item->getPrimaryKey()) {
            $params['where'][] = ["$pk != ?", $item->getPrimaryKey()];
        }
        return $params;
    }

    /**
     * @param Record $item
     * @return array|bool
     */
    public function generateUniqueConditions(Record $item)
    {
        $uniqueFields = $this->getUniqueFields();
        $conditions = [];
        foreach ($uniqueFields as $uniqueName => $fields) {
            $conditions[$uniqueName . '-UNQ'] = $this->generateUniqueCondition($item, $fields);
        }
        return $conditions;
    }

    /**
     * @param Record $item
     * @param $fields
     * @return string
     */
    protected function generateUniqueCondition(Record $item, $fields)
    {
        $conditions = [];
        foreach ($fields as $field) {
            $conditions[] = "`$field` = '{$item->{$field}}'";
        }
        return implode(' AND ', $conditions);
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
