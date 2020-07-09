<?php

namespace Nip\Records\Registry\Traits;

use Nip\Cache\Cacheable\CanCache;

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
    public function offsetSet($key, $value)
    {
        $this->needsCaching(true);
        parent::offsetSet($key, $value);
    }

    public function __destruct()
    {
        $this->checkSaveCache();
    }

    /**
     * @return array
     */
    protected function generateCacheData()
    {
        return $this->all();
    }

    protected function doLoad(): void
    {
        $data = $this->getDataFromCache();
        if (is_array($data)) {
            $this->setItems($data);
        }
    }
}
