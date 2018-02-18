<?php

namespace Nip\Records\Tests;

use Mockery as m;
use Nip\Database\Connections\Connection;
use Nip\Records\Record;
use Nip\Records\RecordManager as Records;
use Nip\Records\Tests\AbstractTest;

/**
 * Class RecordTest
 * @package Nip\Records\Tests
 */
class RecordTest extends AbstractTest
{

    /**
     * @return array
     */
    public function providerGetManagerName()
    {
        return [
            ["Notifications_Table", "Notifications_Tables"],
            ["Donation", "Donations"],
        ];
    }

    /**
     * @dataProvider providerGetManagerName
     * @param string $recordName
     * @param string $managerName
     */
    public function testGetManagerName($recordName, $managerName)
    {
        $this->object->setClassName($recordName);
        self::assertSame($managerName, $this->object->getManagerName());
    }
}
