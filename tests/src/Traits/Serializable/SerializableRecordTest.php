<?php

namespace Nip\Records\Tests\Traits\Serializable;

use Nip\Database\Connections\Connection;
use Nip\Records\Record;
use Nip\Records\RecordManager as Records;
use Nip\Records\Tests\AbstractTest;

/**
 * Class SerializableRecordTest
 * @package Nip\Records\Tests\Traits\Serializable
 */
class SerializableRecordTest extends AbstractTest
{
    public function test_magic_serialize()
    {
        $this->object->property1 = 1;
        $this->object->property2 = false;

        $string = serialize($this->object);
        self::assertIsString($string);

        $object = unserialize($string);
        self::assertSame(1, $object->property1);
        self::assertSame(false, $object->property2);
    }

    protected function setUp(): void
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
