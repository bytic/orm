<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Records\Record;
use Nip\Records\Relations\MorphTo;

/**
 * Class MorphToTest
 * @package Nip\Records\Tests\Relations
 */
class MorphToTest extends \Nip\Records\Tests\AbstractTest
{

    public function testMorphDefaultFieldsGeneration()
    {
        $relation = new MorphTo();
        self::assertEquals('item', $relation->getMorphPrefix());
        self::assertEquals('item_id', $relation->getFK());
        self::assertEquals('item_type', $relation->getMorphTypeField());
    }

    public function testMorphCustomFieldsGeneration()
    {
        $relation = new MorphTo();
        $relation->addParams(['morphPrefix' => 'parent']);
        self::assertEquals('parent', $relation->getMorphPrefix());
        self::assertEquals('parent_id', $relation->getFK());
        self::assertEquals('parent_type', $relation->getMorphTypeField());
    }


    protected function setUp()
    {
        parent::setUp();

//        $this->object = new MorphTo();
//        $this->object->setName('User');
//
//        $user = new Record();
//
//        $users = m::namedMock('Users', 'Nip\Records\RecordManager')->shouldDeferMissing()
//            ->shouldReceive('instance')->andReturnSelf()
//            ->shouldReceive('findOne')->andReturn($user)->getMock();
//
//        $users->setPrimaryFK('id_user');
//
//        $this->_object->setWith($users);
//
//        $article = new Record();
//        $article->id_parent = 3;
//        $article->parent = 3;
//
//        $this->_object->setItem($article);
    }
}
