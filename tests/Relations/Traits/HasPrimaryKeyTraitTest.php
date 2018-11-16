<?php

namespace Nip\Records\Tests\Relations\Traits;

use Nip\Records\RecordManager;
use Nip\Records\Relations\HasMany;
use Nip\Records\Tests\AbstractTest;

/**
 * Class HasPrimaryKeyTraitTest
 * @package Nip\Records\Tests\Relations\Traits
 */
class HasPrimaryKeyTraitTest extends AbstractTest
{

    public function testGetQuery()
    {
        $relation = new HasMany();
        $relation->setName('Books');

        $users = new RecordManager();
        $users->setPrimaryKey('id_test');

        self::assertEquals('id', $relation->getPrimaryKey());

        $relation->setManager($users);
        self::assertEquals('id', $relation->getPrimaryKey());

        $relation->setPrimaryKey(null);
        self::assertEquals('id_test', $relation->getPrimaryKey());
    }
}
