<?php

namespace Nip\Records\Filters\Traits;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Traits\HasFilters\RecordsTrait;

/**
 * Trait HasRecordManagerTrait
 * @package Nip\Records\Filters\Traits
 */
trait HasRecordManagerTrait
{
    /**
     * @var null|RecordManager
     */
    protected $recordManager = null;

    /**
     * @return null|RecordManager
     */
    public function getRecordManager()
    {
        return $this->recordManager;
    }

    /**
     * @param RecordsTrait $recordManager
     */
    public function setRecordManager($recordManager)
    {
        $this->recordManager = $recordManager;
    }
}
