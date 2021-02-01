<?php

namespace Nip\Records\Tests\Collections;

use Mockery\Mock;
use Nip\Records\Collections\Collection;
use Nip\Records\Record;
use Nip\Records\Relations\HasMany;
use Nip\Records\Relations\Relation;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

/**
 * Class CollectionTest
 * @package Nip\Records\Tests\Collections
 */
class CollectionTest extends AbstractTest
{
    public function test_loadRelation_returns_empty_collection()
    {
        $records = Books::instance();

        $relation = new HasMany();
        $relation->setWith($records);

        /** @var Mock|Collection $collection */
        $collection = \Mockery::mock(Collection::class)->makePartial();
        $collection->shouldReceive('getRelation')->with('test')->andReturn($relation);

        $return = $collection->loadRelation('test');
        self::assertInstanceOf(Collection::class, $return);
        self::assertCount(0, $return);
    }

    /**
     * @param $expected
     * @param $index
     * @param $data
     * @dataProvider getRecordKeyData
     */
    public function testGetRecordKey($expected, $index, $data)
    {
        $collection = new Collection();
        $record = Books::instance()->getNew();
        $record->writeData($data);

        self::assertEquals($expected, $collection->getRecordKey($record, $index));
    }

    /**
     * @return array
     */
    public static function getRecordKeyData()
    {
        return [
            ['9', null, ['id' => 9]],
            ['9', 'id', ['id' => 9]],
            ['9-test', ['id', 'name'], ['id' => 9, 'name' => 'test']],
        ];
    }
}
