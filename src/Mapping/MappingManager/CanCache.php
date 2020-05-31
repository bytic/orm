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
    protected $needsCaching = false;

    protected $cacheStore = null;

    /**
     * @param null $needCaching
     * @return bool
     */
    public function needsCaching($needCaching = null): bool
    {
        if (is_bool($needCaching)) {
            $this->needsCaching = $needCaching;
        }
        return $this->needsCaching;
    }

    protected function checkInitFromCache()
    {

    }

    protected function initFromCache()
    {
        $data = $this->cacheStore()->get('orm.mapping.data');
        $this->repository->initFromCache($data);
    }


    protected function checkSaveCache()
    {
        if ($this->needsCaching() !== true) {
            return;
        }
        $data = $this->repository->generateCache();
        $this->cacheStore()->set('orm.mapping.data', $data, DateInterval::createFromDateString('10 years'));
    }


    /**
     * @return Repository
     */
    protected function cacheStore()
    {
        if ($this->cacheStore === null) {
            $this->cacheStore = $this->generateCacheStore();
        }
        return $this->cacheStore;
    }

    /**
     * @return Repository
     */
    protected function generateCacheStore()
    {
        if (function_exists('app')) {
            return app('cache.store');
        }

        return Container::getInstance()->get('cache.store');
    }
}
