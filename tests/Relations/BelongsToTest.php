<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Records\Collections\Collection;
use Nip\Records\Locator\ModelLocator;
use Nip\Records\Record;
use Nip\Records\RecordManager;
use Nip\Records\Relations\BelongsTo;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class BelongsToTest
 * @package Nip\Records\Tests\Relations
 */
class BelongsToTest extends \Nip\Records\Tests\AbstractTest
{

    /**
     * @var BelongsTo
     */
    protected $object;

    public function testInitResults()
    {
        static::assertSame($this->_user, $this->object->getResults());
    }

    public function testGetEagerQuery()
    {
        ModelLocator::instance()->getConfiguration()->addNamespace('Nip\Records\Tests\Fixtures\Records');

        $relation = new BelongsTo();
        $relation->setName('Books');

        static::assertInstanceOf(Books::class, $relation->getWith());
        $collection = new Collection();

        static::assertEquals(
            'SELECT `books`.* FROM `books` WHERE id IN ()',
            $relation->getEagerQuery($collection)->getString()
        );

        $users = new RecordManager();
        $users->setPrimaryKey('id');
        $relation->setManager($users);

        foreach ([3, 4] as $id) {
            $user = new Record();
            $user->id_book = $id;
            $user->setManager($users);
            $collection->add($user);
        }

        static::assertEquals('id_book', $relation->getFK());
        static::assertEquals([3, 4], $relation->getEagerFkList($collection));
        static::assertEquals(
            'SELECT `books`.* FROM `books` WHERE id IN (3, 4)',
            $relation->getEagerQuery($collection)->getString()
        );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->object = new BelongsTo();
        $this->object->setName('User');
        $this->object->addParams(['withPK' => 'id_custom']);

        $this->_user = new Record();

        $users = m::namedMock('Users', 'Nip\Records\RecordManager')->makePartial();
        $users->setPrimaryKey('id');
        $users->setPrimaryFK('id_user');

        $users->shouldReceive('instance')->andReturnSelf();
        $users->shouldReceive('findByField')
            ->withArgs(['id_custom', 3])
            ->andReturn(new Collection([$this->_user]));

//        m::namedMock('User', 'Record');

        $this->object->setWith($users);
        $article = new Record();
        $article->id_user = 3;
        $this->object->setItem($article);
    }
}
