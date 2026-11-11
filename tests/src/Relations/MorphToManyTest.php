<?php

namespace Nip\Records\Tests\Relations;

use Mockery as m;
use Nip\Database\Adapters\MySQLi;
use Nip\Database\Connections\Connection;
use Nip\Records\Record;
use Nip\Records\RecordManager;
use Nip\Records\Relations\MorphToMany;

/**
 * Class MorphToManyTest
 * @package Nip\Records\Tests\Relations
 */
class MorphToManyTest extends \Nip\Records\Tests\AbstractTest
{
    public function testNewQuery()
    {
        $tagsManager = new RecordManager();
        $tagsManager->setTable('tags');

        $tag = new Record();
        $tag->id = 3;

        $page = $this->getPageTestRecord();

        $relation = new MorphToMany();
        $relation->setItem($tag);
        $relation->setManager($tagsManager);
        $relation->setWith($page->getManager());
        $relation->setJoinFields(['test']);

        static::assertSame(
            "SELECT `pages`.`id` AS `id`, `pages`.`id_page` AS `id_page`, `pages_pivot`.`test` AS `__test` "
            . "FROM `pages`, ``.`pages_pivot` "
            . "WHERE `pages_pivot`.`id_page` = `pages`.`id` AND `pages_pivot`.`pivotal_type` = 'tags'",
            $relation->newQuery()->getString()
        );
    }

    public function testSaveDeletesOnlyTheNonInverseOwnerAndMorphType()
    {
        $queries = [];
        $relation = $this->newTestRelation(false, $queries);

        $relation->save();

        self::assertSame(
            ["DELETE FROM `tags_pivot` WHERE `pivotal_id` = 3 AND `pivotal_type` = 'pages'"],
            $queries
        );
    }

    public function testSaveDeletesOnlyTheInverseOwnerAndMorphType()
    {
        $queries = [];
        $relation = $this->newTestRelation(true, $queries);

        $relation->save();

        self::assertSame(
            ["DELETE FROM `tags_pivot` WHERE `id_tag` = 3 AND `pivotal_type` = 'pages'"],
            $queries
        );
    }

    public function testCustomOwnerKeyIsUsedForReadsAndWrites()
    {
        $queries = [];
        $relation = $this->newTestRelation(false, $queries);
        $relation->setFK('custom_owner_id');

        self::assertStringContainsString(
            '`tags_pivot`.`custom_owner_id` = 3',
            $relation->getLinkQuery()->getString()
        );

        $relation->save();

        self::assertSame(
            ["DELETE FROM `tags_pivot` WHERE `custom_owner_id` = 3 AND `pivotal_type` = 'pages'"],
            $queries
        );
    }

    public function testNonInverseAttachAndDetachUseTheSameOwnerKey()
    {
        $queries = [];
        $relation = $this->newTestRelation(false, $queries);
        $relation->setJoinFields(['pivotal_type']);
        $tag = new Record();
        $tag->id = 9;

        self::assertStringContainsString('`tags_pivot`.`pivotal_id` = 3', $relation->getLinkQuery()->getString());
        $relation->attach($tag);
        $relation->detach($tag);

        self::assertSame(
            "INSERT INTO `tags_pivot` (`pivotal_id`,`id_tag`,`pivotal_type`) VALUES (3, 9, 'pages')",
            $queries[0]
        );
        self::assertSame(
            "DELETE FROM `tags_pivot` WHERE (id_tag IN (9) AND pivotal_id = 3) AND `pivotal_type` = 'pages'",
            $queries[1]
        );
    }

    public function testInverseAttachAndDetachUseTheSameOwnerKey()
    {
        $queries = [];
        $relation = $this->newTestRelation(true, $queries);
        $relation->setJoinFields(['pivotal_type']);
        $page = new Record();
        $page->id = 9;

        self::assertStringContainsString('`tags_pivot`.`id_tag` = 3', $relation->getLinkQuery()->getString());
        $relation->attach($page);
        $relation->detach($page);

        self::assertSame(
            "INSERT INTO `tags_pivot` (`id_tag`,`pivotal_id`,`pivotal_type`) VALUES (3, 9, 'pages')",
            $queries[0]
        );
        self::assertSame(
            "DELETE FROM `tags_pivot` WHERE (pivotal_id IN (9) AND id_tag = 3) AND `pivotal_type` = 'pages'",
            $queries[1]
        );
    }

    private function newTestRelation(bool $inverse, array &$queries): MorphToMany
    {
        $adapter = m::mock(MySQLi::class)->makePartial();
        $adapter->shouldReceive('cleanData')->andReturnUsing(function ($value) {
            return $value;
        });
        $adapter->shouldReceive('query')->andReturnUsing(function ($sql) use (&$queries) {
            $queries[] = $sql;
            return true;
        });
        $connection = new Connection(false);
        $connection->setAdapter($adapter);

        $pages = new RecordManager();
        $pages->setTable('pages');
        $pages->setPrimaryKey('id');
        $pages->setPrimaryFK('id_page');
        $pages->setDB($connection);

        $tags = new RecordManager();
        $tags->setTable('tags');
        $tags->setPrimaryKey('id');
        $tags->setPrimaryFK('id_tag');
        $tags->setDB($connection);

        $item = new Record();
        $item->id = 3;

        $relation = new MorphToMany();
        $relation->setInverse($inverse);
        $relation->setManager($inverse ? $tags : $pages);
        $relation->setWith($inverse ? $pages : $tags);
        $relation->setItem($item);
        $relation->setJoinFields([]);

        return $relation;
    }

    /**
     * @return Record
     */
    protected function getPageTestRecord()
    {
        $page = new Record();
        $page->id = 1;

        /** @var RecordManager $pages */
        $pages = m::namedMock('Pages', RecordManager::class)->shouldDeferMissing()
            ->shouldReceive('instance')->andReturnSelf()
            ->shouldReceive('findOne')->andReturn($page)
            ->shouldReceive('getFields')->andReturn(['id','id_page'])
            ->getMock();
        $pages->setPrimaryKey('id');
        $pages->setPrimaryFK('id_page');

        $page->setManager($pages);
        return $page;
    }
}
