<?php

namespace Nip\Records\AbstractModels;

use Nip\HelperBroker;
use \Exception;
use Nip\Records\Traits\ActiveRecord\ActiveRecordTrait;
use Nip\Records\Traits\HasHelpers\HasHelpersRecordTrait;
use Nip\Records\Traits\HasManager\HasManagerRecordTrait;
use Nip\Utility\Traits\NameWorksTrait;

/**
 * Class Row
 * @package Nip\Records\_Abstract
 *
 * @method \Nip_Helper_Url URL()
 */
abstract class Record
{
    use NameWorksTrait;
    use ActiveRecordTrait;
    use HasHelpersRecordTrait;
    use HasManagerRecordTrait;

    protected $_name = null;

    protected $_data;

    public function &__get($name)
    {
        if (!$this->__isset($name)) {
            $this->_data[$name] = null;
        }

        return $this->_data[$name];
    }

    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    public function __unset($name)
    {
        unset($this->_data[$name]);
    }

    /**
     * Overloads Ucfirst() helper
     *
     * @param string $name
     * @param array $arguments
     * @return \Nip\Helpers\AbstractHelper|null
     */
    public function __call($name, $arguments)
    {
        if ($this->isHelperCall($name)) {
            return $this->getHelper($name);
        }

        trigger_error("Call to undefined method $name", E_USER_ERROR);
        return null;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        if ($this->_name == null) {
            $this->_name = inflector()->unclassify(get_class($this));
        }
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
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
        $vars = get_object_vars($this);
        return $vars['_data'];
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
    public function getCloneWithRelations()
    {
        $item = $this->getClone();
        $item->cloneRelations($this);

        return $item;
    }

    /**
     * @return Record
     */
    public function getClone()
    {
        $clone = $this->getManager()->getNew();
        $clone->updateDataFromRecord($this);

        unset($clone->{$this->getManager()->getPrimaryKey()}, $clone->created);

        return $clone;
    }

    /**
     * @param self $record
     */
    public function updateDataFromRecord($record)
    {
        $data = $record->toArray();
        $this->writeData($data);

        unset($this->{$this->getManager()->getPrimaryKey()}, $this->created);
    }

    /**
     * @param bool|array $data
     */
    public function writeData($data = false)
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    /**
     * @return \Nip\Request
     */
    protected function getRequest()
    {
        return $this->getManager()->getRequest();
    }
}
