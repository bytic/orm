<?php

namespace Nip\Records\Relations\Traits;

use Nip\Records\AbstractModels\RecordManager;

/**
 * Trait HasForeignKeyTrait
 * @package Nip\Records\Relations\Traits
 *
 * @method RecordManager getManager()
 */
trait HasPrimaryKeyTrait
{
    /**
     * @var null|string
     */
    protected $primaryKey = null;

    /**
     * @param $params
     */
    public function checkParamPrimaryKey($params)
    {
        if (isset($params['primaryKey'])) {
            $this->setPrimaryKey($params['primaryKey']);
            unset($params['primaryKey']);
        }
    }

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        if ($this->primaryKey == null) {
            $this->initPrimaryKey();
        }

        return $this->primaryKey;
    }

    /**
     * @param $name
     */
    public function setPrimaryKey($name)
    {
        $this->primaryKey = $name;
    }

    protected function initPrimaryKey()
    {
        $this->setPrimaryKey($this->generatePrimaryKey());
    }

    /**
     * @return string
     */
    protected function generatePrimaryKey()
    {
        return $this->hasManager() ? $this->getManager()->getPrimaryKey() : 'id';
    }
}
