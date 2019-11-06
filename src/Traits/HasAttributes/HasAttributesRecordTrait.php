<?php

namespace Nip\Records\Traits\HasAttributes;

use Nip\Utility\Str;

/**
 * Trait HasAttributesRecordTrait
 * @package Nip\Records\Traits\HasAttributes
 */
trait HasAttributesRecordTrait
{
    protected $_data;

    /**
     * @param $name
     * @return mixed
     */
    public function &__get($name)
    {
        if (!$this->__isset($name)) {
            $this->_data[$name] = null;
        }

        return $this->_data[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->setAttribute($name, $value);
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * @param $name
     */
    public function __unset($name)
    {
        unset($this->_data[$name]);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        if (property_exists($this, $key)) {
            $this->{$key} = $value;
            return;
        }

        $method = 'set' . Str::studly($key);
        if (method_exists($this, $key)) {
            $this->$method($value);
            return;
        }
        $method .= 'Attribute';
        if (method_exists($this, $key)) {
            $this->$method($value);
            return;
        }
        $this->setDataValue($key, $value);
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
     * @param $key
     * @param $value
     */
    protected function setDataValue($key, $value)
    {
        $this->_data[$key] = $value;
    }
}
