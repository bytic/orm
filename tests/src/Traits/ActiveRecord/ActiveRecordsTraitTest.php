<?php

namespace Nip\Records\Tests\Traits\ActiveRecord;

use Mockery\Mock;
use Nip\Records\EventManager\Events\Event;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Book;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class ActiveRecordsTraitTest
 * @package Nip\Records\Tests\Traits\ActiveRecord
 */
class ActiveRecordsTraitTest extends AbstractTest
{
    public function test_insert()
    {
        /** @var Mock|Books $books */
        $books = Books::instance();
        $listener = [];
        $books::creating(
            function (Event $event) use (&$listener) {
                $listener[] = $event->getName();
            }
        );
        $books::created(
            function (Event $event) use (&$listener) {
                $listener[] = $event->getName();
            }
        );

        $book = new Book(['test' => 'foe']);
        $book->setManager($books);

        $book->insert();

        self::assertSame(
            [
                'orm.creating: Nip\Records\Tests\Fixtures\Records\Books\Books',
                'orm.created: Nip\Records\Tests\Fixtures\Records\Books\Books'
            ],
            $listener
        );
    }

    public function test_delete()
    {
        /** @var Mock|Books $books */
        $books = Books::instance();
        $listener = [];
        $books::deleting(
            function (Event $event) use (&$listener) {
                $listener[] = $event->getName();
            }
        );
        $books::deleted(
            function (Event $event) use (&$listener) {
                $listener[] = $event->getName();
            }
        );

        $book = new Book(['test' => 'foe']);
        $book->setManager($books);

        $book->delete();

        self::assertSame(
            [
                'orm.deleting: Nip\Records\Tests\Fixtures\Records\Books\Books',
                'orm.deleted: Nip\Records\Tests\Fixtures\Records\Books\Books'
            ],
            $listener
        );
    }

    public function test_isCallDatabaseOperation_dnx()
    {
        /** @var Mock|Books $books */
        $books = \Mockery::mock(Books::class)->makePartial()->shouldAllowMockingProtectedMethods();
//        $books->shouldReceive();

        $this->expectError();
        $books->findByEvent(new \stdClass());
    }
}
