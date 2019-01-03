<?php

namespace Nip\Records\Traits\HasPrimaryKey;

/**
 * Trait RecordsTrait
 * @package Nip\Records\Traits\HasPrimaryKey
 */
trait RecordsTrait
{
    /**
     * @var null|string
     */
    protected $primaryKey = null;

    /**
     * @return string
     */
    public function getPrimaryKey()
    {
        if ($this->primaryKey === null) {
            $this->initPrimaryKey();
        }

        return $this->primaryKey;
    }

    /**
     * @param null|string $primaryKey
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    protected function initPrimaryKey()
    {
        $this->setPrimaryKey($this->generatePrimaryKey());
    }

    /**
     * @return string
     */
    public function generatePrimaryKey()
    {
        $structure = $this->getTableStructure();
        $primaryKey = false;
        if (is_array($structure) && isset($structure['indexes']['PRIMARY']['fields'])) {
            $primaryKey = $structure['indexes']['PRIMARY']['fields'];
            if (count($primaryKey) == 1) {
                $primaryKey = reset($primaryKey);
            }
        }

        return $primaryKey;
    }
}
