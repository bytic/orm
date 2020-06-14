<?php

namespace Nip\Records\Tests\Traits\HasUuid;

use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class HasUuidRecordManagerTrait
 * @package Nip\Records\Tests\Traits\HasUuid
 */
class HasUuidRecordManagerTrait extends AbstractTest
{
    public function test_bootHasUuidRecordManagerTrait()
    {
        $books = Books::instance();
        $book = $books->getNew();
        $book->insert();

        self::assertNotEmpty($book->uuid);
        self::assertSame(36, strlen($book->uuid));
    }

    public function test_creating_notOverwrite()
    {
        $books = Books::instance();
        $book = $books->getNew();
        $book->uuid = '3c21852c-9b93-4cba-a86f-2e507f2ca5d6';
        $book->insert();

        self::assertNotEmpty($book->uuid);
        self::assertSame('3c21852c-9b93-4cba-a86f-2e507f2ca5d6', $book->uuid);
    }
}
