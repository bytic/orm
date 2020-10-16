<?php

namespace Nip\Records\Traits\ActiveRecord;

use Nip\Records\AbstractModels\Record;
use Nip\Records\Traits\HasPrimaryKey\RecordTrait as HasPrimaryKeyTrait;

/**
 * Class ActiveRecordTrait
 * @package Nip\Records\Traits\ActiveRecord
 */
trait ActiveRecordTrait
{
    use HasPrimaryKeyTrait;

    /**
     * @return bool
     */
    public function insert()
    {
        return $this->getManager()->insert($this);
    }

    /**
     * @return bool|\Nip\Database\Result
     */
    public function update()
    {
        $return = $this->getManager()->update($this);
        return $return;
    }

    public function save()
    {
        $this->getManager()->save($this);
    }

    public function saveRecord()
    {
        $this->getManager()->save($this);
    }

    public function delete()
    {
        $this->getManager()->delete($this);
    }

    /**
     * @return bool
     */
    public function isInDB()
    {
        $primaryKey = $this->getManager()->getPrimaryKey();
        return $this->{$primaryKey} > 0;
    }

    /**
     * @return bool|false|Record
     */
    public function exists()
    {
        return $this->getManager()->exists($this);
    }
}
