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
        $pk = $this->getManager()->getPrimaryKey();

        return $this->{$pk};
    }
}
