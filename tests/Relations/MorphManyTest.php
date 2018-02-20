<?php

namespace Nip\Records\Tests\Relations;

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

    public function testGetMorphClassWithGenericManager()
    {
        $relation = new MorphMany();
        $manager = new RecordManager();
        $relation->setManager($manager);

        self::assertEquals('nip-records', $relation->getMorphValue());
    }

    public function testGetQuery()
    {
        ModelLocator::instance()->getConfiguration()->addNamespace('Nip\Records\Tests\Fixtures\Records');

        $relation = new MorphMany();
        $relation->setName('Books');

        $users = new RecordManager();
        $users->setPrimaryKey('id');

        $user = new Record();
        $user->id = 3;
        $user->setManager($users);
        $relation->setItem($user);

        self::assertEquals(
            "SELECT `books`.* FROM `books` WHERE parent_type = 'nip-records' AND `parent_id` = 3",
            $relation->getQuery()->getString()
        );
    }


    public function testMorphDefaultFieldsGeneration()
    {
        $relation = new MorphMany();
        self::assertEquals('parent', $relation->getMorphPrefix());
        self::assertEquals('parent_id', $relation->getFK());
        self::assertEquals('parent_type', $relation->getMorphTypeField());
    }

    public function testMorphCustomFieldsGeneration()
    {
        $relation = new MorphMany();
        $relation->addParams(['morphPrefix' => 'item']);
        self::assertEquals('item', $relation->getMorphPrefix());
        self::assertEquals('item_id', $relation->getFK());
        self::assertEquals('item_type', $relation->getMorphTypeField());
    }
}
