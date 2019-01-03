<?php

namespace Nip\Records\Filters\Traits;

use Nip\Records\Filters\FilterManager;
use Nip\Records\Filters\Sessions\Session;

/**
 * Trait HasSessionsTrait
 * @package Nip\Records\Filters\Traits
 *
 * @method getClonedFilters()
 * @method getRequest()
 */
trait HasSessionsTrait
{
    /**
     * @var Session[]
     */
    protected $sessions = [];

    /**
     * @param null $name
     * @return Session
     * @throws \Exception
     */
    public function getSession($name = null)
    {
        $this->checkRequestSession();
        $name = $name ? $name : FilterManager::DEFAULT_SESSION;
        if (!$this->hasSession($name)) {
            throw new \Exception("Invalid filter session name [{$name}]");
        }
        return $this->sessions[$name];
    }

    /**
     * @param $data
     * @param null $name
     * @return Session
     */
    public function createSession($data, $name = null)
    {
        $session = $this->newSessionFromData($data);
        $this->addSession($session, $name);
        return $session;
    }

    /**
     * @param Session $session
     * @param null $name
     */
    public function addSession($session, $name = null)
    {
        $name = empty($name) ? static::generateSessionNameFromData($session->getData()) : $name;
        $session->setName($name);
        $this->sessions[$name] = $session;
    }

    /**
     * @param $data
     * @return Session
     */
    public function newSessionFromData($data)
    {
        $session = new Session();
        $filters = $this->getClonedFilters();
        $session->setFilters($filters);
        $session->initWithData($data);
        return $session;
    }

    /**
     * @param $data
     * @return string
     */
    public static function generateSessionNameFromData($data)
    {
        return md5(serialize($data));
    }

    protected function checkRequestSession()
    {
        $name = FilterManager::DEFAULT_SESSION;
        if (!$this->hasSession($name)) {
            $this->generateRequestSession($name);
        }
    }

    /**
     * @param $name
     */
    protected function generateRequestSession($name)
    {
        $request = $this->getRequest();
        $this->createSession($request, $name);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function hasSession($name)
    {
        return isset($this->sessions[$name]);
    }
}
