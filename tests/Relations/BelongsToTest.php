<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Records\Record;
use Nip\Records\Relations\BelongsTo;

/**
 * Class BelongsToTest
 * @package Nip\Records\Tests\Relations
 */
class BelongsToTest extends \Nip\Records\Tests\AbstractTest
{

    /**
     * @var BelongsTo
     */
    protected $_object;

    public function testInitResults()
    {
        static::assertSame($this->_user, $this->_object->getResults());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->_object = new BelongsTo();
        $this->_object->setName('User');

        $this->_user = new Record();

        $users = m::namedMock('Users', 'Nip\Records\RecordManager')->shouldDeferMissing()
            ->shouldReceive('instance')->andReturnSelf()
            ->shouldReceive('findOne')->andReturn($this->_user)->getMock();
        $users->setPrimaryFK('id_user');
//        m::namedMock('User', 'Record');

        $this->_object->setWith($users);
        $article = new Record();
        $article->id_user = 3;
        $this->_object->setItem($article);
    }

}
