<?php

namespace Nip\Records\Tests\Traits\TableStructure;

use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class TableStructureRecordsTraitTest
 * @package Nip\Records\Tests\Traits\TableStructure
 */
class TableStructureRecordsTraitTest extends AbstractTest
{
    public function testHasField()
    {
        $manager = Books::instance();
        $manager->setTableStructure(unserialize(file_get_contents(TEST_FIXTURE_PATH . '/database_structure/users.serialize')));

        self::assertTrue($manager->hasField('first_name'));
        self::assertFalse($manager->hasField('no_field'));
    }

    public function test_isAutoincrement()
    {
        $manager = Books::instance();
        $manager->setTableStructure(unserialize(file_get_contents(TEST_FIXTURE_PATH . '/database_structure/users.serialize')));

        self::assertTrue($manager->isAutoincrement('id'));
        self::assertFalse($manager->isAutoincrement('first_name'));
    }
}
