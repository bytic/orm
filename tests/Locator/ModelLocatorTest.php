<?php

namespace Nip\Records\Tests\Locator;

use Nip\Records\Locator\Exceptions\InvalidModelException;
use Nip\Records\Locator\ModelLocator;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class ModelLocatorTest
 * @package Nip\Records\Tests
 */
class ModelLocatorTest extends AbstractTest
{

    public function testGetClassFullName()
    {
        $manager = ModelLocator::get(Books::class);
        self::assertInstanceOf(Books::class, $manager);
    }

    public function testGetInvalidAlias()
    {
        self::expectException(InvalidModelException::class);
        ModelLocator::get('ClassNotExist');
    }

    public function testGetClassFromConfigNamespace()
    {
        ModelLocator::instance()->getConfiguration()->addNamespace('Nip\Records\Tests\Fixtures\Records');

        $manager = ModelLocator::get('Books');
        self::assertInstanceOf(Books::class, $manager);

        $manager = ModelLocator::get('Books\Books');
        self::assertInstanceOf(Books::class, $manager);
        $manager = ModelLocator::get('books');
        self::assertInstanceOf(Books::class, $manager);
    }
}