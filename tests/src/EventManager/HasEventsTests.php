<?php

namespace Nip\Records\Tests\EventManager;

use Nip\Records\AbstractModels\Record;
use Nip\Records\EventManager\EventManager;
use Nip\Records\EventManager\Events\Event;
use Nip\Records\EventManager\Events\Observe;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class HasEventsTests
 * @package Nip\Records\Tests\EventManager
 */
class HasEventsTests extends AbstractTest
{
    /**
     * @dataProvider data_registerModelEvent
     * @param $event
     */
    public function test_registerModelEvent($event)
    {
        $called = 0;
        Books::{$event}(function (Event $event) use (&$called) {
            self::assertInstanceOf(Event::class, $event);
            self::assertInstanceOf(Record::class, $event->getRecord());
            $called++;
        });

        $books = Books::instance();
        $book = $books->getNew();

        $books->triggerModelEvent($event, $book);

        self::assertSame(1, $called);
    }

    /**
     * @return array
     */
    public function data_registerModelEvent()
    {
        $events = Observe::all();
        $data = [];
        foreach ($events as $event) {
            $data[] = [$event];
        }
        return $data;
    }

}