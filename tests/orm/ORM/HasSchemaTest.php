<?php

declare(strict_types=1);

namespace ByTIC\ORM\Tests\ORM;

use ByTIC\ORM\ORM;
use ByTIC\ORM\Schema\SchemaInterface;
use Nip\Records\Tests\AbstractTest;

/**
 * Class HasSchemaTest
 * @package ByTIC\ORM\Tests\ORM
 */
class HasSchemaTest extends AbstractTest
{
    public function test_schema_static()
    {
        $schema = ORM::schema();
        self::assertInstanceOf(SchemaInterface::class, $schema);
    }
}
