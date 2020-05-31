<?php

namespace Nip\Records\Mapping;

use Nip\Collections\Collection;

/**
 * Class MappingRepository
 * @package Nip\Records\Mapping
 */
class MappingRepository extends Collection
{
    /**
     * @param $data
     */
    public function initFromCache($data)
    {
        if (!is_string($data) || strlen($data) < 1) {
            return;
        }
        $data = unserialize($data);
        if (!is_array($data) || count($data) < 1) {
            return;
        }
        $this->setItems($data);
    }

    /**
     * @return string
     */
    public function generateCache()
    {
        return serialize($this->all());
    }
}
