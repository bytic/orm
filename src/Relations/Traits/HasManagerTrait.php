<?php

namespace Nip\Records\Relations\Traits;

use Nip\Records\AbstractModels\Record;
use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Traits\Relations\HasRelationsRecordsTrait;

/**
 * Class HasManagerTrait
 * @package Nip\Records\Relations\Traits
 *
 * @method Record getItem()
 */
trait HasManagerTrait
{

    /**
     * @var RecordManager
     */
    protected $manager = null;

    /**
     * @return RecordManager|\Nip\Records\RecordManager
     */
    public function getManager()
    {
        if ($this->manager == null) {
            $this->initManager();
        }

        return $this->manager;
    }

    /**
     * @param HasRelationsRecordsTrait $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return void
     */
    public function initManager()
    {
        $this->manager = $this->getItem()->getManager();
    }

    /**
     * @return bool
     */
    public function hasManager()
    {
        return $this->manager instanceof RecordManager;
    }
}
