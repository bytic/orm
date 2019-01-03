<?php

namespace Nip\Records\Tests\Traits\HasPrimaryKey;

use Nip\Database\Connections\Connection;
use Nip\Records\Record;
use Nip\Records\RecordManager as Records;
use Nip\Records\Tests\AbstractTest;

/**
 * Class RecordTest
 * @package Nip\Records\Tests\Traits\HasPrimaryKey
 */
class RecordTraitTest extends AbstractTest
{
    public function testGetPrimaryKeySimple()
    {
        $this->object->getManager()->setPrimaryKey('id');
        $this->object->id = 99;

        static::assertEquals('99', $this->object->getPrimaryKey());
    }

    public function testGetPrimaryKeyComposite()
    {
        $this->object->getManager()->setPrimaryKey(['id_parent', 'type']);
        $this->object->id_parent = 99;
        $this->object->type = 'book';

        static::assertEquals(['id_parent' => 99, 'type' => 'book'], $this->object->getPrimaryKey());
    }

    protected function setUp()
    {
        parent::setUp();
        $wrapper = new Connection(false);

        $manager = new Records();
        $manager->setDB($wrapper);
        $manager->setTable('pages');

        $this->object = new Record();
        $this->object->setManager($manager);
    }
}
