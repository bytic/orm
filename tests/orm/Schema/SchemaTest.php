<?php

declare(strict_types=1);

namespace ByTIC\ORM\Tests\Schema;

use ByTIC\ORM\Schema\Schema;
use Nip\Records\Tests\AbstractTest;

/**
 * Class SchemaTest
 * @package ByTIC\ORM\Tests\Schema
 */
class SchemaTest extends AbstractTest
{
    public function test_with_no_data()
    {
        $schema = new Schema();
        self::assertInstanceOf(Schema::class, $schema);
    }

    public function test_serialize()
    {
        $data = require TEST_FIXTURE_PATH . '/schemas/basic.php';
        $schema = new Schema($data);
        self::assertInstanceOf(Schema::class, $schema);

        $serialized = serialize($schema);
        self::assertSame(
            'C:23:"ByTIC\ORM\Schema\Schema":187:{a:2:{i:0;a:0:{}i:1;a:1:{s:5:"books";a:2:{s:6:"entity";s:50:"ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book";s:10:"repository";s:46:"Nip\Records\Tests\Fixtures\Records\Books\Books";}}}}',
            $serialized
        );

        $schema2 = unserialize($serialized);
        self::assertEquals($schema, $schema2);
    }
}
