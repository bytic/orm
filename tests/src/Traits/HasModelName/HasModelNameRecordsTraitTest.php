<?php

namespace Nip\Records\Tests\Traits\HasModelName;

use Mockery\Mock;
use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Book;
use Nip\Records\Tests\Fixtures\Records\Books\Books;
use Nip\Records\Tests\Fixtures\Records\Books\Chapters\BooksChapter;
use Nip\Records\Tests\Fixtures\Records\Books\Chapters\BooksChapters;
use Nip\Records\Tests\Fixtures\Records\Purchases\PurchasableRecord;
use Nip\Records\Tests\Fixtures\Records\Purchases\PurchasableRecordManager;

/**
 * Class HasControllerRecordsTraitTest
 * @package Nip\Records\Tests\Traits\HasModelName
 */
class HasModelNameRecordsTraitTest extends AbstractTest
{
    /**
     * @param string $manager
     * @param string $name
     * @dataProvider data_getController_for_namespace
     */
    public function test_getController_for_namespace($manager, $name)
    {
        /** @var Mock|RecordManager $manager */
        $manager = call_user_func($manager . '::instance');
        self::assertSame($name, $manager->getModel());
    }

    public function data_getController_for_namespace(): array
    {
        return [
            [Books::class, Book::class],
            [BooksChapters::class, BooksChapter::class],
            [PurchasableRecordManager::class, PurchasableRecord::class],
        ];
    }
}
