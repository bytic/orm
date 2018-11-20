<?php

namespace Nip\Records\Tests\Traits\HasManager;

use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Book;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class HasManagerRecordTest
 * @package Nip\Records\Tests\Traits\HasManager
 */
class HasManagerRecordTest extends AbstractTest
{
    public function testGetManager()
    {
        $book = new Book();

        $manager = $book->getManager();
        self::assertInstanceOf(Books::class, $manager);
    }

    public function testGetManagerName()
    {
        $book = new Book();
        self::assertSame('Nip\Records\Tests\Fixtures\Records\Books\Books', $book->getManagerName());
    }
}
