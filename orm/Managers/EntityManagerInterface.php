<?php

declare(strict_types=1);

namespace ByTIC\ORM\Managers;

use Doctrine\Persistence\ObjectManager;

/**
 * Interface EntityManagerInterface
 * @package ByTIC\ORM\Managers
 */
interface EntityManagerInterface extends ObjectManager
{
    /**
     * @param $className
     * @return bool
     */
    public function hasRepository($className) : bool;
}
