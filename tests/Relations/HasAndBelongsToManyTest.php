<?php

namespace Nip\Records\Tests\Relations;

use Nip\Records\RecordManager;
use Nip\Records\Relations\HasAndBelongsToMany;

/**
 * Class HasAndBelongsToManyTest
 * @package Nip\Records\Tests\Relations
 */
class HasAndBelongsToManyTest extends \Nip\Records\Tests\AbstractTest
{
    public function testTableNameGeneration()
    {
        $tableA = new RecordManager();
        $tableA->setTable('tableA');

        $tableB = new RecordManager();
        $tableB->setTable('tableB');

        $relation = new HasAndBelongsToMany();
        $relation->setManager($tableA);
        $relation->setWith($tableB);
        self::assertEquals('tableA_tableB', $relation->getTable());

        $relation = new HasAndBelongsToMany();
        $relation->setManager($tableB);
        $relation->setWith($tableA);
        self::assertEquals('tableA_tableB', $relation->getTable());
    }
}
