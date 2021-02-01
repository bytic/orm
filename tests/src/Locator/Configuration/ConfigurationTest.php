<?php

namespace Nip\Records\Tests\Locator\Configuration;

use Nip\Records\Locator\Configuration\Configuration;
use Nip\Records\Tests\AbstractTest;

/**
 * Class ConfigurationTest
 * @package Nip\Records\Tests\Locator\Configuration
 */
class ConfigurationTest extends AbstractTest
{
    public function test_getNamespaces_empty()
    {
        $configuration = new Configuration();

        self::assertFalse($configuration->hasNamespaces());
        self::assertSame([], $configuration->getNamespaces());
    }

    public function test_addNamespace()
    {
        $configuration = new Configuration();
        $configuration->addNamespace('\App');

        self::assertTrue($configuration->hasNamespaces());
        self::assertSame(['\App'], $configuration->getNamespaces());
    }

    public function test_prependNamespace()
    {
        $configuration = new Configuration();
        $configuration->addNamespace('\App');
        $configuration->prependNamespace('\App2');

        self::assertTrue($configuration->hasNamespaces());
        self::assertSame(['\App2','\App'], $configuration->getNamespaces());
    }
}
