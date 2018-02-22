<?php

namespace Nip\Records\Locator\Resolver\Pipeline\Stages;

use Nip\Records\AbstractModels\RecordManager;

/**
 * Class NamespaceSearch
 * @package Nip\Records\Locator\Resolver\Pipeline\Stages
 */
class NamespaceSearch extends AbstractStage
{
    /**
     * @return void
     * @throws \Exception
     */
    public function processCommand()
    {
        if ($this->getCommand()->hasNamespaces()) {
            $this->loadFromNamespaces();
        }
    }

    protected function loadFromNamespaces()
    {
        $classes = $this->buildNamespaceClasses();
        foreach ($classes as $class) {
            if ($this->isValidClassName($class)) {
                $manager = $this->newModelManager($class);
                $this->getCommand()->setInstance($manager);
                return;
            }
        }
    }

    /**
     * @return array
     */
    public function buildNamespaceClasses()
    {
        $namespaces = $this->getCommand()->getNamespaces();
        $classes = [];
        $aliasVariations = $this->buildAliasVariations($this->getCommand()->getAlias());
        foreach ($namespaces as $namespace) {
            foreach ($aliasVariations as $variation) {
                $classes[] = $namespace . '\\' . $variation;
            }
        }
        return $classes;
    }

    /**
     * @param $alias
     * @return array
     */
    public function buildAliasVariations($alias)
    {
        $alias = str_replace('\\', '-', $alias);
        $alias = inflector()->classify($alias);
        $alias = ucwords(preg_replace('/[^A-Z^a-z^0-9]+/', ' ', $alias));
        $elements = explode(" ", $alias);

        $return[] = implode('\\', $elements);

        $elements[] = end($elements);
        $return[] = implode('\\', $elements);

        return $return;
    }
}
