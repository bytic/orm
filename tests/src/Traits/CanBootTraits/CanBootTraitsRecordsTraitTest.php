<?php

namespace Nip\Records\Tests\Traits\CanBootTraits;

use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class CanBootTraitsRecordsTraitTest
 * @package Nip\Records\Tests\Traits\CanBootTraits
 */
class CanBootTraitsRecordsTraitTest extends AbstractTest
{
    public function test_getBootTraits()
    {
        $books = Books::instance();
        $bootTraits = $books->getBootTraits();
        self::assertEquals(
            ['bootTimestampableManagerTrait', 'bootTimestampableTrait', 'bootHasUuidRecordManagerTrait'],
            $bootTraits
        );
    }
}
