<?php

namespace Nip\Records\Tests\Traits\Relations;

use Mockery as m;
use Nip\Database\Connections\Connection;
use Nip\Http\Request;
use Nip\Records\RecordManager as Records;
use Nip\Records\Relations\BelongsTo;
use Nip\Records\Relations\HasAndBelongsToMany;
use Nip\Records\Relations\HasMany;
use Nip\Records\Relations\HasOne;
use Nip\Records\Relations\MorphToMany;
use Nip\Records\Tests\AbstractTest;
use Nip\Records\Tests\Fixtures\Records\Books\Books;
use Nip\Records\Tests\Fixtures\Records\Books\Chapters\BooksChapters;
use Nip\Records\Traits\Relations\HasRelationsRecordsTrait;

/**
 * Class RecordsTest
 * @package Nip\Records\Tests
 *
 * @property HasRelationsRecordsTrait $object
 */
class RecordsTest extends AbstractTest
{
    /**
     * @dataProvider dataRelationClasses
     * @param $class
     * @param $name
     */
    public function testGetRelationClass($class, $name)
    {
        self::assertEquals($class, $this->object->getRelationClass($name));
    }

    /**
     * @dataProvider dataRelationClasses
     * @param $class
     * @param $name
     */
    public function testNewRelation($class, $name)
    {
        self::assertInstanceOf($class, $this->object->newRelation($name));
    }

    /**
     * @return array
     */
    public function dataRelationClasses()
    {
        return [
            [BelongsTo::class, 'BelongsTo'],
            [BelongsTo::class, 'belongsTo'],
            [HasOne::class, 'HasOne'],
            [HasOne::class, 'hasOne'],
            [HasMany::class, 'HasMany'],
            [HasMany::class, 'hasMany'],
            [HasAndBelongsToMany::class, 'HasAndBelongsToMany'],
            [HasAndBelongsToMany::class, 'hasAndBelongsToMany'],
        ];
    }

    public function test_morphedByMany()
    {
        $this->object->morphedByMany('Users', []);

        $relation = $this->object->getRelation('Users');
        self::assertInstanceOf(MorphToMany::class, $relation);
    }

    public function test_cloneRelations()
    {
        $bookManager = Books::instance();

        $from = $bookManager->getNew();
        $chapters = $from->getRelation('Chapters')->newCollection();
        $chapters[] = BooksChapters::instance()->getNew(['name' => 'chapter1']);
        $chapters[] = BooksChapters::instance()->getNew(['name' => 'chapter2']);
        $from->getRelation('Chapters')->setResults($chapters);
        $to = $bookManager->getNew();

        $bookManager->cloneRelations($from, $to);

        $chaptersCloned = $to->getRelation('Chapters')->getResults();
        self::assertCount(2, $chaptersCloned);
        self::assertSame('chapter1', $chaptersCloned[0]->name);
        self::assertSame('chapter2', $chaptersCloned[1]->name);
    }


    protected function _testInitRelationsFromArrayBelongsToUser($name)
    {
        self::assertTrue($this->object->hasRelation($name));
        self::assertInstanceOf(BelongsTo::class, $this->object->getRelation($name));
        self::assertInstanceOf(Records::class, $this->object->getRelation($name)->getWith());

        self::assertEquals(
            $this->object->getRelation($name)->getWith()->getPrimaryFK(),
            $this->object->getRelation($name)->getFK()
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $wrapper = new Connection(null);

        $this->object = m::mock(Records::class)->makePartial()
            ->shouldReceive('getRequest')->andReturn(Request::create('/'))
            ->getMock();

        $this->object->setDB($wrapper);
        $this->object->setTable('pages');
    }
}
