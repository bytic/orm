<?php

namespace Nip\Records\Tests\Traits\Relations;

use Mockery as m;
use Nip\Database\Connections\Connection;
use Nip\Records\RecordManager as Records;
use Nip\Records\Relations\BelongsTo;
use Nip\Records\Relations\HasAndBelongsToMany;
use Nip\Records\Relations\HasMany;
use Nip\Records\Traits\Relations\HasRelationsRecordsTrait;
use Nip\Request;
use Nip\Records\Tests\AbstractTest;

/**
 * Class RecordsTest
 * @package Nip\Records\Tests
 *
 * @property HasRelationsRecordsTrait $object
 */
class RecordsTest extends AbstractTest
{
    public function testGetRelationClass()
    {
        self::assertEquals(BelongsTo::class, $this->object->getRelationClass('BelongsTo'));
        self::assertEquals(BelongsTo::class, $this->object->getRelationClass('belongsTo'));

        self::assertEquals(HasMany::class, $this->object->getRelationClass('HasMany'));
        self::assertEquals(HasMany::class, $this->object->getRelationClass('hasMany'));

        self::assertEquals(HasAndBelongsToMany::class, $this->object->getRelationClass('HasAndBelongsToMany'));
        self::assertEquals(HasAndBelongsToMany::class, $this->object->getRelationClass('hasAndBelongsToMany'));
    }

    public function testNewRelation()
    {
        self::assertInstanceOf(BelongsTo::class, $this->object->newRelation('BelongsTo'));
        self::assertInstanceOf(BelongsTo::class, $this->object->newRelation('belongsTo'));

        self::assertInstanceOf(HasMany::class, $this->object->newRelation('HasMany'));
        self::assertInstanceOf(HasMany::class, $this->object->newRelation('hasMany'));

        self::assertInstanceOf(HasAndBelongsToMany::class, $this->object->newRelation('HasAndBelongsToMany'));
        self::assertInstanceOf(HasAndBelongsToMany::class, $this->object->newRelation('hasAndBelongsToMany'));
    }

//    public function testInitRelationsFromArrayBelongsToSimple()
//    {
    /** @var Records $users */
//        $users = m::namedMock('Users', Records::class)->shouldDeferMissing()
//            ->shouldReceive('instance')->andReturnSelf()
//            ->getMock();

//        $users->setPrimaryFK('id_user');
//
//        m::namedMock('User', Records::class);
//        m::namedMock('Articles', Records::class);

//        $this->object->setPrimaryFK('idobject');
//
//        $this->object->initRelationsFromArray('belongsTo', ['User']);
//        $this->_testInitRelationsFromArrayBelongsToUser('User');
//
//        $this->object->initRelationsFromArray('belongsTo', [
//            'UserName' => ['with' => $users],
//        ]);
//        $this->_testInitRelationsFromArrayBelongsToUser('UserName');
//
//        self::assertSame($users, $this->object->getRelation('User')->getWith());
//    }

    protected function _testInitRelationsFromArrayBelongsToUser($name)
    {
        self::assertTrue($this->object->hasRelation($name));
        self::assertInstanceOf(BelongsTo::class, $this->object->getRelation($name));
        self::assertInstanceOf(Records::class, $this->object->getRelation($name)->getWith());

        self::assertEquals(
            $this->object->getRelation($name)->getWith()->getPrimaryFK(),
            $this->object->getRelation($name)->getFK()
        );
    }

    protected function setUp()
    {
        parent::setUp();

        $wrapper = new Connection(null);

        $this->object = m::mock(Records::class)->shouldDeferMissing()
            ->shouldReceive('getRequest')->andReturn(Request::create('/'))
            ->getMock();

        $this->object->setDB($wrapper);
        $this->object->setTable('pages');
    }
}
