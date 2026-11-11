<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Records\Collections\Collection;
use Nip\Records\RecordManager;
use Nip\Records\Relations\HasAndBelongsToMany;
use Nip\Records\Tests\Fixtures\Records\Books\Book;
use Nip\Records\Tests\Fixtures\Records\Books\Books;
use Nip\Records\Tests\Fixtures\Records\Shelves\Shelves;

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

    public function testEmptyRecordLoadRelation()
    {
        $record = new Book();
        $record->setManager(Books::instance());

        $relation = new HasAndBelongsToMany();
        $relation->setWith(Shelves::instance());
        $relation->setItem($record);
        $results = $relation->getResults();
        self::assertInstanceOf(Collection::class, $results);
        self::assertCount(0, $results);
    }

    public function testHasResultsForUnloadedNullEmptyAndNonEmptyResults()
    {
        $relation = new HasAndBelongsToMany();

        self::assertFalse($relation->hasResults());
        self::assertFalse($relation->isPopulated());

        $relation->setResults(null);
        self::assertFalse($relation->hasResults());

        $relation->setResults(new Collection());
        self::assertFalse($relation->hasResults());

        $relation->setResults(new Collection([new Book()]));
        self::assertTrue($relation->hasResults());
    }

    public function testSaveWithNullResultsDoesNotInsertConnections()
    {
        $relation = m::mock(HasAndBelongsToMany::class)->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $relation->shouldReceive('deleteConnections')->once();
        $relation->shouldReceive('newInsertQuery')->never();
        $relation->setResults(null);

        self::assertSame($relation, $relation->save());
    }
}
