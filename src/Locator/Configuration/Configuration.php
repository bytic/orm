<?php

namespace Nip\Records\Locator\Configuration;

/**
 * Class Configuration
 * @package Nip\Records\Locator
 */
class Configuration
{
    protected $namespaces = [];

    /**
     * @param $namespace
     */
    public function addNamespace($namespace)
    {
        $this->namespaces[] = $namespace;
    }

    /**
     * @return array
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * @return bool
     */
    public function hasNamespaces()
    {
        return count($this->namespaces) > 0;
    }

    /**
     * @param array $namespaces
     */
    public function setNamespaces(array $namespaces): void
    {
        $this->namespaces = $namespaces;
    }
}
