<?php

namespace Nip\Records\Relations\Traits;

use Nip\Records\AbstractModels\RecordManager;

/**
 * Trait HasPivotForeignKey
 * @package Nip\Records\Relations\Traits
 *
 * @method RecordManager getWith
 */
trait HasPivotForeignKey
{
    /**
     * @var null|string
     */
    protected $pivotForeignKey = null;

    /**
     * @param $params
     */
    public function checkParamPivotFk($params)
    {
        if (isset($params['pivotFK'])) {
            $this->setPivotFK($params['pivotFK']);
            unset($params['pivotFK']);
        }
    }


    /**
     * @return string
     */
    public function getPivotFK()
    {
        if ($this->pivotForeignKey == null) {
            $this->initPivotFK();
        }

        return $this->pivotForeignKey;
    }

    /**
     * @param $name
     */
    public function setPivotFK($name)
    {
        $this->pivotForeignKey = $name;
    }

    protected function initPivotFK()
    {
        $this->setPivotFK($this->generatePivotFK());
    }

    /**
     * @return string
     */
    protected function generatePivotFK()
    {
        return $this->getWith()->getPrimaryFK();
    }
}
