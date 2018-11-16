<?php

namespace Nip\Records\Relations\Traits;

use Nip\Records\AbstractModels\RecordManager;

/**
 * Trait HasPivotPrimaryKey
 * @package Nip\Records\Relations\Traits
 *
 * @method RecordManager getWith
 */
trait HasPivotPrimaryKey
{
    /**
     * @var null|string
     */
    protected $pivotPrimaryKey = null;

    /**
     * @param $params
     */
    public function checkParamPivotPrimaryKey($params)
    {
        if (isset($params['pivotPrimaryKey'])) {
            $this->setPivotPrimaryKey($params['pivotPrimaryKey']);
            unset($params['pivotPrimaryKey']);
        }
    }


    /**
     * @return string
     */
    public function getPivotPrimaryKey()
    {
        if ($this->pivotPrimaryKey == null) {
            $this->initPivotPrimaryKey();
        }

        return $this->pivotPrimaryKey;
    }

    /**
     * @param $name
     */
    public function setPivotPrimaryKey($name)
    {
        $this->pivotPrimaryKey = $name;
    }

    protected function initPivotPrimaryKey()
    {
        $this->setPivotPrimaryKey($this->generatePivotPrimaryKey());
    }

    /**
     * @return string
     */
    protected function generatePivotPrimaryKey()
    {
        return $this->getWith()->getPrimaryKey();
    }
}
