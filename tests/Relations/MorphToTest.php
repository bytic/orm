<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Records\Collections\Collection;
use Nip\Records\Locator\ModelLocator;
use Nip\Records\Record;
use Nip\Records\RecordManager;
use Nip\Records\Relations\Exceptions\ModelNotLoadedInRelation;
use Nip\Records\Relations\MorphTo;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;
use Nip\Records\Tests\Fixtures\Records\Shelves\Shelves;

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
        $relation->addParams(['morphPrefix' => 'item']);
        self::assertEquals('item', $relation->getMorphPrefix());
        self::assertEquals('item_id', $relation->getFK());
        self::assertEquals('item_type', $relation->getMorphTypeField());
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

    public function testGetEagerQuery()
    {
        ModelLocator::instance()->getConfiguration()->addNamespace('Nip\Records\Tests\Fixtures\Records');

        $relation = new MorphTo();
        $relation->setName('Book');

        $books = ModelLocator::get('books');

        $users = new RecordManager();
        $users->setPrimaryKey('id');
        $relation->setManager($users);

        $collection = new Collection();

        foreach ([3, 4] as $id) {
            $user = new Record();
            $user->parent_type = 'books';
            $user->parent_id = $id;
            $user->setManager($users);
            $collection->add($user);
        }

        static::assertEquals('parent_id', $relation->getFK());
        static::assertEquals([3, 4], $relation->getEagerFkList($collection));
        static::assertEquals(
            'SELECT `books`.* FROM `books` WHERE id IN (3, 4)',
            $relation->getEagerQueryType($collection, $books)->getString()
        );
    }

    public function testGetEagerQueryType()
    {
        ModelLocator::instance()->getConfiguration()->addNamespace('Nip\Records\Tests\Fixtures\Records');

        $relation = new MorphTo();

        $users = new RecordManager();
        $users->setPrimaryKey('id');
        $relation->setManager($users);

        $collection = new Collection();

        $user = new Record();
        $user->parent_type = 'books';
        $user->parent_id = 3;
        $user->setManager($users);
        $collection->add($user);

        $user = new Record();
        $user->parent_type = 'shelves';
        $user->parent_id = 4;
        $user->setManager($users);
        $collection->add($user);

        static::assertEquals(
            'SELECT `books`.* FROM `books` WHERE id IN (3)',
            $relation->getEagerQueryType($collection, new Books())->getString()
        );

        static::assertEquals(
            'SELECT `shelves`.* FROM `shelves` WHERE id IN (4)',
            $relation->getEagerQueryType($collection, new Shelves())->getString()
        );
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
