<?php

namespace Nip\Records\Tests\Traits\Relations;

use Mockery as m;
use Nip\Database\Connections\Connection;
use Nip\Records\Record;
use Nip\Records\RecordManager as Records;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Traits\Relations\HasRelationsRecordTrait;

/**
 * Class RecordTest
 * @package Nip\Records\Tests
 *
 * @property HasRelationsRecordTrait $object
 */
class RecordTest extends AbstractTest
{
    public function testNewRelation()
    {
        $users = m::namedMock('Users', Records::class)->shouldDeferMissing()
            ->shouldReceive('instance')->andReturnSelf()
            ->getMock();

        m::namedMock('User', Record::class);

        $manager = $this->object->getManager();
        $relation = $manager->belongsTo('User');
        $relation->setWith($users);

        $relation = $this->object->newRelation('User');

        static::assertSame($this->object, $relation->getItem());
        static::assertSame($users, $relation->getWith());
    }

    protected function setUp(): void
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
