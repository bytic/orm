<?php

namespace Nip\Records\Relations\Traits;

use Nip\Records\RecordManager;

/**
 * Trait HasForeignKeyTrait
 * @package Nip\Records\Relations\Traits
 *
 * @method RecordManager getManager()
 */
trait HasForeignKeyTrait
{
    /**
     * @var null|string
     */
    protected $fk = null;

    /**
     * @param $params
     */
    public function checkParamFk($params)
    {
        if (isset($params['fk'])) {
            $this->setFK($params['fk']);
            unset($params['fk']);
        }
    }

    /**
     * @return string
     */
    public function getFK()
    {
        if ($this->fk == null) {
            $this->initFK();
        }

        return $this->fk;
    }

    /**
     * @param $name
     */
    public function setFK($name)
    {
        $this->fk = $name;
    }

    protected function initFK()
    {
        $this->setFK($this->generateFK());
    }

    /**
     * @return string
     */
    protected function generateFK()
    {
        return $this->getManager()->getPrimaryFK();
    }
}
