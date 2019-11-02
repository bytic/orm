<?php

namespace Nip\Records\Traits\HasHelpers;

use Nip\HelperBroker;

/**
 * Trait HasHelpersRecordTrait
 * @package Nip\Records\Traits\HasHelpers
 */
trait HasHelpersRecordTrait
{
    /**
     * @deprecated
     * @var array
     */
    protected $_helpers = [];

    /**
     * @param $name
     * @return bool
     */
    public function isHelperCall($name)
    {
        return $name === ucfirst($name);
    }

    /**
     * @param string $name
     * @return \Nip\Helpers\AbstractHelper
     */
    public function getHelper($name)
    {
        return HelperBroker::get($name);
    }
}
