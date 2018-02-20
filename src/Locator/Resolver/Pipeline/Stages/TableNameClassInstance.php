<?php

namespace Nip\Records\Locator\Resolver\Pipeline\Stages;

/**
 * Class TableNameClassInstance
 * @package Nip\Records\Locator\Resolver\Pipeline\Stages
 */
class TableNameClassInstance extends AbstractStage
{
    /**
     * @return void
     * @throws \Exception
     */
    public function processCommand()
    {
        $class = static::generateClass($this->getCommand()->getAlias());
        if ($this->isValidClassName($class)) {
            $manager = $this->newModelManager($class);
            $this->getCommand()->setInstance($manager);
        }
    }

    /**
     * @param $name
     * @return string
     */
    public static function generateClass($name)
    {
        $class = inflector()->classify($name);
        $elements = explode("_", $class);
        $class = implode("_", $elements);

        return $class;
    }
}
