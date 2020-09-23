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
}
