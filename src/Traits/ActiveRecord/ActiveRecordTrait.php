<?php

namespace Nip\Records\Traits\ActiveRecord;

use Nip\Records\AbstractModels\Record;
use Nip\Records\Traits\AbstractTrait\RecordTrait;

/**
 * Class ActiveRecordTrait
 * @package Nip\Records\Traits\ActiveRecord
 */
trait ActiveRecordTrait
{
    use RecordTrait;

    protected $_dbData = [];

    /**
     * @param bool|array $data
     */
    public function writeDBData($data = false)
    {
        foreach ($data as $key => $value) {
            $this->_dbData[$key] = $value;
        }
    }

    /**
     * @return array
     */
    public function getDBData()
    {
        return $this->_dbData;
    }

    /**
     * @return bool
     */
    public function insert()
    {
        $pk = $this->getManager()->getPrimaryKey();
        $lastId = $this->getManager()->insert($this);
        if ($pk == 'id') {
            $this->{$pk} = $lastId;
        }

        return $lastId > 0;
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