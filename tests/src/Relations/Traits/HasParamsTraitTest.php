<?php

namespace Nip\Records\Tests\Relations\Traits;

use Nip\Records\Relations\BelongsTo;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Trait HasParamsTraitTest
 * @package Nip\Records\Tests\Relations\Traits
 */
class HasParamsTraitTest extends AbstractTest
{

    public function test_setParam_class()
    {
        $relation = new BelongsTo();
        $relation->setName('test');
        $relation->setParams(['class' => Books::class]);

        static::assertInstanceOf(Books::class, $relation->getWith());
    }

    public function test_setParam_with()
    {
        $relation = new BelongsTo();
        $relation->setName('test');

        $books = Books::instance();
        $relation->setParams(['with' => $books]);

        static::assertInstanceOf(Books::class, $relation->getWith());
        static::assertSame($books, $relation->getWith());
    }

    public function test_setParam_withPK()
    {
        $relation = new BelongsTo();
        $relation->setName('test');

        $relation->setParams(['withPK' => 'id_pk']);

        static::assertSame('id_pk', $relation->getWithPK());
    }

    public function test_setParam_table()
    {
        $relation = new BelongsTo();
        $relation->setName('test');

        $relation->setParams(['table' => 'my_table']);

        static::assertSame('my_table', $relation->getTable());
    }

    public function test_setParam_fk()
    {
        $relation = new BelongsTo();
        $relation->setName('test');

        $relation->setParams(['fk' => 'my_table']);

        static::assertSame('my_table', $relation->getFK());
    }

    public function test_setParam_primaryKey()
    {
        $relation = new BelongsTo();
        $relation->setName('test');

        $relation->setParams(['primaryKey' => 'my_table']);

        static::assertSame('my_table', $relation->getPrimaryKey());
    }

}