<?php

namespace Nip\Records\Tests\Traits\Unique;

use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Book;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class RecordsTraitTest
 * @package Nip\Records\Tests\Traits\Unique
 */
class RecordsTraitTest extends AbstractTest
{

    public function testExists()
    {
        $manager = $this->getManagerWithUnique();
        $manager->shouldReceive('findOneByParams')
            ->andReturnUsing(function ($argument) {
                return $argument;
            });


        $item = new Book();

        self::assertSame(
            ['where' => ["`id_event` = '' AND `id_volunteer` = '' OR `hash` = ''"]],
            $manager->exists($item)
        );
    }

    public function testGenerateExistsParams()
    {
        $manager = $this->getManagerWithUnique();
        $item = new Book();

        $params = $manager->generateExistsParams($item);
        self::assertSame(
            [
                'where' => [
                    'id_event_id_volunteer-UNQ' => "`id_event` = '' AND `id_volunteer` = ''",
                    'hash-UNQ' => "`hash` = ''"
                ],
            ],
            $params
        );
    }

    public function testGetUniqueFields()
    {
        $manager = $this->getManagerWithUnique();

        $unique = $manager->getUniqueFields();
        self::assertSame(
            [
                'id_event_id_volunteer' => [
                    0 => 'id_event',
                    1 => 'id_volunteer'
                ],
                'hash' => [
                    0 => 'hash'
                ]
            ],
            $unique
        );
    }

    /**
     * @return Books|\Mockery\MockInterface
     */
    protected function getManagerWithUnique()
    {
        $manager = \Mockery::mock(Books::class)->makePartial();

        $structure = require TEST_FIXTURE_PATH
            . DIRECTORY_SEPARATOR . 'database_structure' . DIRECTORY_SEPARATOR . 'table_with_unique.php';
        $manager->setTableStructure($structure);
        return $manager;
    }
}
