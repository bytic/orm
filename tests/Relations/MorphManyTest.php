<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Records\Locator\ModelLocator;
use Nip\Records\Record;
use Nip\Records\RecordManager;
use Nip\Records\Relations\MorphMany;
use Nip\Records\Tests\AbstractTest;

/**
 * Class MorphManyTest
 * @package Nip\Records\Tests\Relations
 */
class MorphManyTest extends AbstractTest
{

    public function testGetMorphClass()
    {
        $relation = new MorphMany();
        $manager = new RecordManager();
        $relation->setManager($manager);

        self::assertEquals('Nip\Records\RecordManager', $relation->getMorphValue());
    }

    public function testGetQuery()
    {
        ModelLocator::instance()->getConfiguration()->addNamespace('Nip\Records\Tests\Fixtures\Records');

        $relation = new MorphMany();
        $relation->setName('Books');

        $users = new RecordManager();
        $user = new Record();
        $user->id = 3;
        $user->setManager($users);
        $relation->setItem($user);

        self::assertEquals(
            '++',
            $relation->getQuery()->getString()
        );
    }
}
