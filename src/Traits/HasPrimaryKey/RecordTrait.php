<?php

namespace Nip\Records\Traits\HasPrimaryKey;

/**
 * Trait RecordTrait
 * @package Nip\Records\Traits\HasPrimaryKey
 */
trait RecordTrait
{
    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        $primaryKey = $this->getManager()->getPrimaryKey();

        if (is_array($primaryKey)) {
            return $this->generateCompositePrimaryKey($primaryKey);
        }

        return $this->{$primaryKey};
    }

    /**
     * @param null $primaryKey
     * @return array
     */
    protected function generateCompositePrimaryKey($primaryKey = null)
    {
        $primaryKey = $primaryKey ? $primaryKey : $this->getManager()->getPrimaryKey();
        $return = [];
        foreach ($primaryKey as $key) {
            $return[$key] = $this->{$key};
        }
        return $return;
    }

    /**
     * @return bool
     */
    public function hasPrimaryKey()
    {
        $key = $this->getPrimaryKey();
        if (is_array($key)) {
            $key = array_filter($key);
        }
        return !empty($key);
    }
}
