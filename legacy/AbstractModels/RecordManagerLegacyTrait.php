<?php

namespace Nip\Records\Legacy\AbstractModels;

/**
 * Trait RecordManagerLegacyTrait
 * @package Nip\Records\Legacy\AbstractModels
 */
trait RecordManagerLegacyTrait
{

    /**
     * @return \Nip\Request
     * @deprecated use request()
     */
    public function getRequest()
    {
        return request();
    }
}