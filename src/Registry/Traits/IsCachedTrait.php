<?php

declare(strict_types=1);

namespace Nip\Records\Registry\Traits;

use Nip\Cache\Cacheable\CanCache;
use Nip\Records\Instantiator\Instantiator;

/**
 * Trait IsCachedTrait
 * @package Nip\Records\Registry\Traits
 */
trait IsCachedTrait
{
    use CanCache;

    /**
     * @inheritDoc
     */
    public function offsetSet($key, $value): void
    {
        $this->needsCaching(true);
        parent::offsetSet($key, $value);
    }

    public function __destruct()
    {
        $this->saveDataToCache($this->generateCacheData());
        $this->checkSaveCache();
    }

    /**
     * @return array
     */
    protected function generateCacheData()
    {
        return array_map(
            function ($manager) {
                return get_class($manager);
            },
            $this->all()
        );
    }

    protected function doLoad(): void
    {
        $data = $this->getDataFromCache();

        $instantiator = new Instantiator();
        $instantiator->setModelRegistry($this);

        if (is_array($data) && count($data)) {
            $data = array_map(
                function ($manager) use ($instantiator) {
                    return $instantiator->instantiate($manager);
                },
                $data
            );
            $this->setItems($data);
        }
    }

    protected function dataCacheKey($key = null)
    {
        return 'orm.registry.data';
    }
}
