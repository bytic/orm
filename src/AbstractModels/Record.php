<?php

namespace Nip\Records\AbstractModels;

use ByTIC\DataObjects\BaseDto;
use Nip\Helpers\Traits\HasHelpersTrait;
use Nip\Records\Traits\ActiveRecord\ActiveRecordTrait;
use Nip\Records\Traits\HasManager\HasManagerRecordTrait;
use Nip\Records\Traits\HasUrl\HasUrlRecordTrait;
use Nip\Utility\Traits\NameWorksTrait;

/**
 * Class Row
 * @package Nip\Records\_Abstract
 *
 * @method \Nip_Helper_Url URL()
 */
abstract class Record extends BaseDto implements \Serializable
{
    use NameWorksTrait;
    use ActiveRecordTrait;
    use HasHelpersTrait;
    use HasManagerRecordTrait;

    use \ByTIC\DataObjects\Behaviors\Serializable\SerializableTrait;
    use \ByTIC\DataObjects\Behaviors\Timestampable\TimestampableTrait;

    use HasUrlRecordTrait;

    protected $serializable = ['attributes'];

    /**
     * Overloads Ucfirst() helper
     *
     * @param string $name
     * @param array $arguments
     * @return \Nip\Helpers\AbstractHelper|null|mixed
     */
    public function __call($name, $arguments)
    {
        /** @noinspection PhpAssignmentInConditionInspection */
        if ($return = $this->isCallUrl($name, $arguments)) {
            return $return;
        }

        if ($this->isHelperCall($name)) {
            return $this->getHelper($name);
        }

        trigger_error("Call to undefined method $name", E_USER_ERROR);
        return null;
    }

    /**
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this->toArray());
    }

    /**
     * @return mixed
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * @param $fields
     * @param string $glue
     * @return string
     */
    public function implodeFields($fields, $glue = '-')
    {
        $return = [];
        foreach ($fields as $field) {
            $return[] = $this->{$field};
        }
        return implode($glue, $return);
    }

    /**
     * @return mixed
     */
    public function toApiArray()
    {
        $data = $this->toArray();
        return $data;
    }

    /**
     * @return Record
     */
    public function getClone()
    {
        $clone = $this->getManager()->getNew();
        $clone->updateDataFromRecord($this);

        return $clone;
    }

    /**
     * @param self $record
     */
    public function updateDataFromRecord($record)
    {
        $data = $record->toArray();
        $cleanup = [$this->getManager()->getPrimaryKey(), 'created'];
        foreach ($cleanup as $key) {
            unset($data[$key]);
        }

        $this->writeData($data);
    }

    /**
     * @return \Nip\Http\Request
     */
    protected function getRequest()
    {
        return request();
    }
}
