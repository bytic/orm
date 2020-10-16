<?php

namespace Nip\Records\Tests\Traits\ActiveRecord;

use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Book;

/**
 * Class ActiveRecordTraitTest
 * @package Nip\Records\Tests\Traits\ActiveRecord
 */
class ActiveRecordTraitTest extends AbstractTest
{
    public function testFieldUpdatedFromDb()
    {
        $book = new Book();
        self::assertTrue($book->fieldUpdatedFromDb('name'));

        $book->writeDBData(['name' => 1]);
        self::assertTrue($book->fieldUpdatedFromDb('name'));

        $book->name = 1;
        self::assertFalse($book->fieldUpdatedFromDb('name'));

        $book->name = '1';
        self::assertFalse($book->fieldUpdatedFromDb('name'));

        $book->name = '2';
        self::assertTrue($book->fieldUpdatedFromDb('name'));
    }
}
