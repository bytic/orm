<?php

namespace Nip\Records\Tests\Instantiator;

use Nip\Records\Locator\ModelLocator;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class CanInstantiatorTraitTest
 * @package Nip\Records\Tests\Instantiator
 */
class CanInstantiatorTraitTest extends AbstractTest
{
    public function test_singleton()
    {
        $manager = new Books();
        ModelLocator::set(Books::class, $manager);
        $this->assertSame($manager, Books::instance());
    }
}
