<?php

namespace Nip\Records\Traits\HasAttributes;

use Nip\Utility\Str;

/**
 * Trait HasAttributesRecordTrait
 * @package Nip\Records\Traits\HasAttributes
 */
trait HasAttributesRecordTrait
{
    protected $attributes = [];

    /**
     * The attributes that should use mutators.
     *
     * @var array
     */
    protected $mutators = [
        'get' => [],
        'set' => [],
    ];

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getAttribute($name);
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
     * @param $key
     * @return bool
     */
    public function __isset($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return !is_null($this->getAttribute($offset));
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->getAttribute($offset);
    }

    /**
     * Set the value for a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->setAttribute($offset, $value);
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
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
     * Get an attribute from the model.
     *
     * @param string $key
     * @return mixed|void
     */
    public function getAttribute(string $key)
    {
        if (!$key) {
            return;
        }

        if (array_key_exists($key, $this->attributes)
//            || array_key_exists($key, $this->casts)
            || $this->hasGetMutator($key)
//            || $this->isClassCastable($key)
        ) {
            return $this->getAttributeValue($key);
        }
//        return $this->getRelationValue($key);
        return;
    }

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param string $key
     * @return mixed
     */
    public function getAttributeValue(string $key)
    {
        return $this->transformModelValue($key, $this->getAttributeFromArray($key));
    }

    /**
     * Transform a raw model value using mutators, casts, etc.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function transformModelValue(string $key, $value)
    {
        // If the attribute has a get mutator, we will call that then return what
        // it returns as the value, which is useful for transforming values on
        // retrieval from the model to a form that is more useful for usage.
        if ($this->hasGetMutator($key)) {
            return $this->mutateAttribute($key, $value);
        }

        // If the attribute exists within the cast array, we will convert it to
        // an appropriate native PHP type dependent upon the associated value
        // given with the key in the pair. Dayle made this comment line up.
//        if ($this->hasCast($key)) {
//            return $this->castAttribute($key, $value);
//        }
        return $value;
    }

    /**
     * Get an attribute from the $attributes array.
     *
     * @param string $key
     * @return mixed
     */
    protected function getAttributeFromArray(string $key)
    {
        return $this->getAttributes()[$key] ?? null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        // First we will check for the presence of a mutator for the set operation
        // which simply lets the developers tweak the attribute as it is set on
        // the model, such as "json_encoding" an listing of data for storage.
        if ($this->hasSetMutator($key)) {
            $this->setMutatedAttributeValue($key, $value);
            return;
        }

        if (property_exists($this, $key)) {
            $this->{$key} = $value;
        }

        $this->setDataValue($key, $value);
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param string $key
     * @return bool
     */
    public function hasSetMutator(string $key): bool
    {
        return $this->hasMutator('set', $key);
    }

    /**
     * Set the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function setMutatedAttributeValue(string $key, $value)
    {
        $method = $this->mutators['set'][$key];
        $this->{$method}($value);
    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        return $this->attributes;
    }


    /**
     * @param $key
     * @param $value
     */
    protected function setDataValue($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param string $key
     * @return bool
     */
    public function hasGetMutator(string $key): bool
    {
        return $this->hasMutator('get', $key);
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function mutateAttribute(string $key, $value)
    {
        $method = $this->mutators['get'][$key];
        return $this->$method($value);
    }


    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param string $type
     * @param string $key
     * @return bool
     */
    public function hasMutator(string $type, string $key): bool
    {
        if (!isset($this->mutators[$type][$key])) {
            $method = $type . Str::studly($key);
            $mutator = false;
            if (method_exists($this, $method)) {
                $mutator = $method;
            } else {
                $method .= 'Attribute';
                if (method_exists($this, $method)) {
                    $mutator = $method;
                }
            }
            $this->mutators[$type][$key] = $mutator;
        }
        return $this->mutators[$type][$key];
    }
}
