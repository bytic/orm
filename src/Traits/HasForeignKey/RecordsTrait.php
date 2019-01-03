<?php

namespace Nip\Records\Traits\HasForeignKey;

/**
 * Trait RecordsTrait
 * @package Nip\Records\Traits\HasForeignKey
 */
trait RecordsTrait
{
    /**
     * @var null|string
     */
    protected $foreignKey = null;

    /**
     * The name of the field used as a foreign key in other tables
     * @return string
     */
    public function getPrimaryFK()
    {
        if ($this->foreignKey == null) {
            $this->initPrimaryFK();
        }

        return $this->foreignKey;
    }

    public function initPrimaryFK()
    {
        $this->setForeignKey($this->generatePrimaryFK());
    }

    /**
     * @param string $foreignKey
     */
    public function setForeignKey($foreignKey)
    {
        $this->foreignKey = $foreignKey;
    }

    /**
     * @return string
     */
    public function generatePrimaryFK()
    {
        $singularize = inflector()->singularize($this->getController());

        return $this->getPrimaryKey() . "_" . inflector()->underscore($singularize);
    }

    /**
     * @param $fk
     */
    public function setPrimaryFK($fk)
    {
        $this->foreignKey = $fk;
    }
}
