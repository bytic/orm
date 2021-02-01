<?php

namespace Nip\Records\Tests\Locator;

use Nip\Records\Locator\Exceptions\InvalidModelException;
use Nip\Records\Locator\ModelLocator;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;
use Nip\Records\Tests\Fixtures\Records\Books\Chapters\BooksChapters;

/**
 * Class ModelLocatorTest
 * @package Nip\Records\Tests
 */
class ModelLocatorTest extends AbstractTest
{
    public function test_getClassFullName()
    {
        $manager = ModelLocator::get(Books::class);
        self::assertInstanceOf(Books::class, $manager);
    }

    public function test_getInvalidAlias()
    {
        self::expectException(InvalidModelException::class);
        ModelLocator::get('ClassNotExist');
    }

    public function test_getManager_with_closure()
    {
        $alias = function () {
            return Books::class;
        };

        $manager = ModelLocator::get($alias);
        self::assertInstanceOf(Books::class, $manager);
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

    public function testGetClassFromModelRegistry()
    {
        ModelLocator::instance()->getConfiguration()->addNamespace('Nip\Records\Tests\Fixtures\Records');

        $manager = ModelLocator::get('Books');
        self::assertInstanceOf(Books::class, $manager);
        $manager->singleton = 'valid';
        self::assertEquals('valid', $manager->singleton);

        $manager = ModelLocator::get('Books\Books');
        self::assertInstanceOf(Books::class, $manager);
        self::assertEquals('valid', $manager->singleton);
    }

    public function testGetModelWithFolderAndComposeName()
    {
        ModelLocator::instance()->getConfiguration()->addNamespace('Nip\Records\Tests\Fixtures\Records');

        $manager = ModelLocator::get('books-chapters');
        self::assertInstanceOf(BooksChapters::class, $manager);
    }
}
