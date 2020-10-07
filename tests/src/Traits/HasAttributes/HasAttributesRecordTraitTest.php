<?php

namespace Nip\Records\Tests\Traits\HasAttributes;

use Nip\Records\Tests\Fixtures\Records\Books\Book;

/**
 * Class HasAttributesRecordTraitTest
 * @package Nip\Records\Tests\Traits\HasAttributes
 */
class HasAttributesRecordTraitTest extends \Nip\Records\Tests\AbstractTest
{
    public function test_field_append()
    {
        $book = new Book();
        self::assertNull($book->field);

        $book->field = [];
        self::assertIsArray($book->field);

        $book->field = '99';
        $book->field .= '99';
        self::assertSame('9999', $book->field);

        $book->field = 10;
        $book->field += 10;
        self::assertSame(20, $book->field);
    }

    public function test_hasSetMutator()
    {
        $book = new Book();
        self::assertTrue($book->hasSetMutator('name'));
        self::assertTrue($book->hasSetMutator('title'));
        self::assertFalse($book->hasSetMutator('author'));
    }

    public function test_setMutatedAttributeValue()
    {
        $book = new Book();
        $book->title = 'title';
        self::assertSame('TITLE', $book->title);
    }
}
