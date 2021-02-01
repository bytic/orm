<?php

declare(strict_types=1);

namespace ByTIC\ORM;

/**
 * Class ORM
 * @package ByTIC\ORM
 */
class ORM implements ORMInterface
{
    /**
     * @const
     */
    public const MANAGER_FALLBACK = '__DEFAULT__';

    use ORM\Facade;
    use ORM\HasRepositories;
    use ORM\HasConnections;
    use ORM\HasManagers;
    use ORM\HasSchema;

    public function __construct()
    {
        $this->initSchema();
    }
}
