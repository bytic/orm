<?php

namespace Nip\Records\Tests\EventManager\Events;

use Nip\Records\EventManager\Events\Event;
use Nip\Records\EventManager\Events\Observe;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class EventTest
 * @package Nip\Records\Tests\EventManager\Events
 */
class EventTest extends AbstractTest
{
    public function test_getName()
    {
        $manager = Books::instance();
        $event = Event::create(Observe::RETRIEVED, $manager);

        self::assertSame('orm.retrieved: Nip\Records\Tests\Fixtures\Records\Books\Books', $event->getName());
    }
}