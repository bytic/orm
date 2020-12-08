<?php

namespace Nip\Records\Traits\HasController;

/**
 * Trait HasControllerRecordsLegacyTrait
 * @package Nip\Records\Traits\HasController
 * @deprecated
 */
trait HasControllerRecordsLegacyTrait
{
    /**
     * @return string
     * @deprecated migrated to namify repo
     */
    protected function generateControllerNamespaced()
    {
        $class = $this->getModelNamespacePath();
        $class = trim($class, '\\');

        return inflector()->unclassify($class);
    }

    /**
     * @return string
     * @deprecated migrated to namify repo
     */
    protected function generateControllerGeneric()
    {
        $class = $this->getClassName();

        return inflector()->unclassify($class);
    }
}
