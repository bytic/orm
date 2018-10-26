<?php

namespace Nip\Records\Filters\Filters\Traits;

use Nip\Records\Filters\FilterManager;

/**
 * Trait HasManagerTrait
 * @package Nip\Records\Filters\Filters\Traits
 */
trait HasManagerTrait
{

    /**
     * @var FilterManager
     */
    protected $manager;

    /**
     * @return null|FilterManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param FilterManager $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }
}
