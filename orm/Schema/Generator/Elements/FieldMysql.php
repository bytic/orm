<?php

declare(strict_types=1);

namespace ByTIC\ORM\Schema\Generator\Elements;

use ByTIC\ORM\Exception\SchemaException;
use ByTIC\ORM\Schema\Elements\FieldSchema;

/**
 * Class FieldMysql
 * @package ByTIC\ORM\Schema\Generator\Elements
 */
class FieldMysql
{
    /**
     * @param array $data
     * @return FieldSchema
     */
    public static function fromMysqlArray($data = []): FieldSchema
    {
        $data = array_change_key_case($data, CASE_LOWER);

        $name = isset($data['field']) ? $data['field'] : null;
        if (empty($name)) {
            throw new SchemaException('Field does not have column name');
        }

        $field = new FieldSchema($name);
        static::parseNull($field, $data);
        return $field;
    }

    /**
     * @param $field
     * @param $data
     */
    protected static function parseNull(&$field, $data)
    {
        $field->set = isset($data['null']) && ($data['null'] == 'YES' || $data == true);
    }
}
