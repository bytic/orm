<?php

namespace Nip\Records\Filters\Filters\Traits;

use Nip\Records\Filters\Sessions\Session;
use Nip\Records\Filters\Sessions\Traits\HasFiltersTrait;

/**
 * Trait HasSessionTrait
 * @package Nip\Records\Filters\Filters\Traits
 */
trait HasSessionTrait
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @return null|Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param Session|HasFiltersTrait $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return bool
     */
    public function hasSession()
    {
        return $this->session instanceof Session;
    }
}
