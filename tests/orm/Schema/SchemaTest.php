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
}
