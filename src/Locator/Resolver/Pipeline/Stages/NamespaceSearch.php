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
        $aliasProcessed = str_replace('\\', '-', $alias);
        $aliasProcessed = inflector()->classify($aliasProcessed);
        $aliasProcessed = ucwords(preg_replace('/[^A-Z^a-z^0-9]+/', ' ', $aliasProcessed));

        $elements = explode(" ", $aliasProcessed);

        $return[] = implode('\\', $elements);

        $lastElement = array_pop($elements);
        $base = trim(implode('\\', $elements) . '\\' . $lastElement, '\\');
        $return[] = $base . '\\' . $lastElement;

        $preLastElement = array_pop($elements);
        if ($preLastElement) {
            $return[] = $base . '\\' . $preLastElement . $lastElement;
        }

        $aliasProcessed = str_replace('-', '_', $alias);
        $aliasProcessed = str_replace('\\', '-', $aliasProcessed);
        $aliasProcessed = inflector()->classify($aliasProcessed);
        $aliasProcessed = ucwords(preg_replace('/[^A-Z^a-z^0-9]+/', ' ', $aliasProcessed));
        $elements = explode(" ", $aliasProcessed);

        $elements[] = end($elements);
        $return[] = implode('\\', $elements);

//        $elements[] = end($elements);
//        $return[] = implode('\\', $elements);

        return array_unique($return);
    }
}
