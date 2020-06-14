<?php

namespace Nip\Records\Tests\EventManager;

use Nip\Records\EventManager\EventManager;
use Nip\Records\EventManager\Events\Event;
use Nip\Records\EventManager\Events\Observe;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Book;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class EventManagerTest
 * @package Nip\Records\Tests\EventManager
 */
class EventManagerTest extends AbstractTest
{
    public function test_dispatch()
    {
        $manager = Books::instance();

        $emanager = EventManager::instance();

        $called = 0;
        $emanager->registerModelEvent(
            Observe::RETRIEVED,
            $manager,
            function () use (&$called) {
                $called++;
            }
        );

        $event = Event::create(Observe::RETRIEVED, $manager);
        $emanager->dispatch($event);
        $emanager->dispatch($event);

        self::assertSame(2, $called);
    }
}
