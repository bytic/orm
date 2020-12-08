<?php

namespace Nip\Records\Tests\Traits\HasController;

use Mockery\Mock;
use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;
use Nip\Records\Tests\Fixtures\Records\Books\Chapters\BooksChapters;

/**
 * Class HasControllerRecordsTraitTest
 * @package Nip\Records\Tests\Traits\HasController
 */
class HasControllerRecordsTraitTest extends AbstractTest
{
    /**
     * @param string $manager
     * @param string $controller
     * @dataProvider data_getController_for_namespace
     */
    public function test_getController_for_namespace($manager, $controller)
    {
        /** @var Mock|RecordManager $manager */
        $manager = call_user_func($manager . '::instance');
        self::assertSame($controller, $manager->getController());
    }

    public function data_getController_for_namespace(): array
    {
        return [
            [Books::class, 'books'],
            [BooksChapters::class, 'books-chapters'],
        ];
    }
}
