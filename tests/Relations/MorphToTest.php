<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Records\Locator\ModelLocator;
use Nip\Records\Record;
use Nip\Records\Relations\Exceptions\ModelNotLoadedInRelation;
use Nip\Records\Relations\MorphTo;
use Nip\Records\Tests\AbstractTest;

/**
 * Class MorphToTest
 * @package Nip\Records\Tests\Relations
 *
 * @property MorphTo $object
 */
class MorphToTest extends AbstractTest
{

    public function testMorphDefaultFieldsGeneration()
    {
        $relation = new MorphTo();
        self::assertEquals('parent', $relation->getMorphPrefix());
        self::assertEquals('parent_id', $relation->getFK());
        self::assertEquals('parent_type', $relation->getMorphTypeField());
    }

    public function testMorphCustomFieldsGeneration()
    {
        $relation = new MorphTo();
        $relation->addParams(['morphPrefix' => 'parent']);
        self::assertEquals('parent', $relation->getMorphPrefix());
        self::assertEquals('parent_id', $relation->getFK());
        self::assertEquals('parent_type', $relation->getMorphTypeField());
    }

    /**
     * @throws ModelNotLoadedInRelation
     */
    public function testGetWithClassWithoutItem()
    {
        $relation = new MorphTo();
        $relation->setName('test');
        $this->expectException(ModelNotLoadedInRelation::class);
        $relation->getWithClass();
    }

    /**
     * @throws ModelNotLoadedInRelation
     */
    public function testGetWithClass()
    {
        $relation = new MorphTo();

        $article = new Record();
        $relation->setItem($article);

        $article->writeData(['parent_id' => 3, 'parent_type' => 'users']);
        self::assertEquals('users', $relation->getWithClass());

        $article->writeData(['parent_id' => 3, 'parent_type' => 'book']);
        self::assertEquals('books', $relation->getWithClass());
    }

    public function testGetResults()
    {
        $this->setUpCompleteRelation();

        $user = $this->object->getResults();

        self::assertInstanceOf(Record::class, $user);
        self::assertSame(3, $user->id);
    }


    protected function setUpCompleteRelation()
    {
        $this->object = new MorphTo();
        $this->object->setName('User');

        $user = new Record();
        $user->id = 3;

        $users = m::namedMock('Users', 'Nip\Records\RecordManager')->shouldDeferMissing()
            ->shouldReceive('instance')->andReturnSelf()
            ->shouldReceive('findOne')->with(3)->andReturn($user)
            ->getMock();

        ModelLocator::set('users', $users);

        $article = new Record();
        $article->parent_id = 3;
        $article->parent_type = 'users';

        $this->object->setItem($article);
    }
}
