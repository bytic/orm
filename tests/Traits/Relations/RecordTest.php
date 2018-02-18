<?php

namespace Nip\Records\Tests\Traits\Relations;

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

    public function testNewRelation()
    {
        $users = m::namedMock('Users', Records::class)->shouldDeferMissing()
            ->shouldReceive('instance')->andReturnSelf()->getMock();

        m::namedMock('User', Record::class);

        $this->object->getManager()->initRelationsFromArray('belongsTo', ['User']);

        $relation = $this->object->newRelation('User');
        static::assertSame($users, $relation->getWith());
        static::assertSame($this->object, $relation->getItem());
    }

    protected function setUp()
    {
        parent::setUp();
        $wrapper = new Connection(false);

        $manager = new Records();
        $manager->setDB($wrapper);
        $manager->setTable('pages');

        $this->object = new Record();
        $this->object->setManager($manager);
    }
}
