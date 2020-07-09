<?php

namespace Nip\Records\Mapping\MappingManager;

use DateInterval;
use Nip\Cache\Stores\Repository;
use Nip\Container\Container;

/**
 * Trait CanCache
 * @package Nip\Records\Mapping\MappingManager
 */
trait CanCache
{
    use \Nip\Cache\Cacheable\CanCache;

    /**
     * @return mixed
     */
    protected function generateCacheData()
    {
        return $this->repository->generateCache();
    }

    protected function initFromCache()
    {
        $data = $this->getDataFromCache();
        $this->repository->initFromCache($data);
    }

    /**
     * @inheritDoc
     */
    protected function dataCacheKey($key= null)
    {
        return 'orm.mapping.data';
    }
}
