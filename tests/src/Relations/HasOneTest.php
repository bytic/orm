<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Records\Collections\Collection;
use Nip\Records\Record;
use Nip\Records\RecordManager;
use Nip\Records\Relations\HasOne;

/**
 * Class HasOneTest
 * @package Nip\Records\Tests\Relations
 */
class HasOneTest extends \Nip\Records\Tests\AbstractTest
{
    protected $_user;

    public function testInitResults()
    {
        static::assertSame($this->_user, $this->object->getResults());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->object = new HasOne();
        $this->object->setName('User');
        $this->object->addParams(['withPK' => 'id_custom']);

        $this->_user = new Record();

        $users = m::namedMock('Users', \Nip\Records\RecordManager::class)->makePartial();
        $users->setPrimaryKey('id');

        $users->shouldReceive('instance')->andReturnSelf();
        $users->shouldReceive('findByField')
            ->withArgs(['id_user', 3])
            ->andReturn(new Collection([$this->_user]));

        $this->object->setWith($users);

        $articleManager = new RecordManager();
        $articleManager->setPrimaryKey('id');
        $articleManager->setPrimaryFK('id_user');

        $article = new Record();
        $article->id = 3;
        $article->setManager($articleManager);

        $this->object->setItem($article);
    }
}
