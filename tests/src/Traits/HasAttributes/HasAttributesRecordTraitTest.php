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

    public function test_setMutatedAttributeValue()
    {
        $book = new Book();
        $book->title = 'title';
        self::assertSame('TITLE', $book->title);
    }

    public function test_mutateAttribute()
    {
        $book = new Book();
        $book->author = 'author';
        self::assertSame('AUTHOR', $book->author);
    }

    public function test_accessor_magic_property()
    {
        $book = new Book();
        $book->category = 'test';
        self::assertSame('test', $book->category);
        self::assertSame('test', $book->getCategory());
        self::assertSame('test', $book->get('category'));
    }
}
