<?php

namespace Nip\Records\AbstractModels;

use Nip\HelperBroker;
use \Exception;
use Nip\Records\Traits\ActiveRecord\ActiveRecordTrait;
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
    use HasManagerRecordTrait;

    protected $_name = null;

    protected $_helpers = [];

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
        if ($name === ucfirst($name)) {
            return $this->getHelper($name);
        }

        trigger_error("Call to undefined method $name", E_USER_ERROR);
        return null;
    }

    /**
     * @param string $name
     * @return \Nip\Helpers\AbstractHelper
     */
    public function getHelper($name)
    {
        return HelperBroker::get($name);
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
     * @return mixed
     */
    public function getPrimaryKey()
    {
        $pk = $this->getManager()->getPrimaryKey();

        return $this->{$pk};
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
