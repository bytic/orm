<?php

declare(strict_types=1);

namespace ByTIC\ORM\Tests\Schema\Generator\Elements;

use ByTIC\ORM\Schema\Elements\FieldSchema;
use ByTIC\ORM\Schema\Generator\Elements\FieldMysql;
use Nip\Records\Tests\AbstractTest;

/**
 * Class FieldMysqlTest
 * @package ByTIC\ORM\Tests\Schema\Elements\Table
 */
class FieldMysqlTest extends AbstractTest
{
    public function test_fromArray_id()
    {
        $data = require TEST_FIXTURE_PATH . '/database_structure/table_with_unique.php';

        $column = FieldMysql::fromMysqlArray($data['fields']['id']);
        self::assertInstanceOf(FieldSchema::class, $column);
        self::assertSame('id', $column->getName());
    }
}
