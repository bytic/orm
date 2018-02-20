<?php

namespace Nip\Records\Tests\Locator\Resolver\Pipeline\Stages;

use Nip\Records\Locator\Resolver\Pipeline\Stages\TableNameClassInstance;
use Nip\Records\Tests\AbstractTest;

/**
 * Class TableNameClassInstanceTest
 * @package Nip\Records\Tests\Locator\Resolver\Pipeline\Stages
 */
class TableNameClassInstanceTest extends AbstractTest
{

    /**
     * @dataProvider generateClassProvider
     * @param $name
     * @param $class
     */
    public function testGetClassFullName($name, $class)
    {
        self::assertEquals($class, TableNameClassInstance::generateClass($name));
    }

    /**
     * @return array
     */
    public function generateClassProvider()
    {
        return [
            ['race-entries','Race_Entries'],
            ['race_entries','RaceEntries'],
            ['race_entries-logs','RaceEntries_Logs']
        ];
    }

}