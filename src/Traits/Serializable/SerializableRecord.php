<?php

namespace Nip\Records\Traits\Serializable;

/**
 * Trait SerializableRecord
 * @package Nip\Records\Traits\Serializable
 */
trait SerializableRecord
{
    /**
     * @return string
     */
    public function serialize()
    {
        $properties = $this->__sleep();
        $data = [];
        foreach ($properties as $property) {
            $data[$property] = $this->{$property};
        }
        return serialize($data);
    }

    /**
     * @param $data
     */
    public function unserialize($data)
    {
        $data = unserialize($data);
        if (!is_array($data)) {
            return;
        }
        foreach ($data as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['attributes'];
    }

    public function __wakeup()
    {
    }
}
