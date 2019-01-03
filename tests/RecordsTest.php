<?php

namespace Nip\Records\Tests;

use Mockery as m;
use Nip\Database\Connections\Connection;
use Nip\Helpers\View\Url;
use Nip\Records\Collections\Collection;
use Nip\Records\RecordManager as Records;
use Nip\Request;
use Nip\Records\Tests\AbstractTest;
use Nip_Helper_Url;

/**
 * Class RecordsTest
 * @package Nip\Records\Tests
 */
class RecordsTest extends AbstractTest
{

    /**
     * @var Records
     */
    protected $_object;

    public function testSetModel()
    {
        $this->_object->setModel('Row');
        self::assertEquals($this->_object->getModel(), 'Row');

        $this->_object->setModel('Row2');
        self::assertEquals($this->_object->getModel(), 'Row2');
    }

    public function testGetFullNameTable()
    {
        self::assertEquals('pages', $this->_object->getFullNameTable());

        $this->_object->getDB()->setDatabase('database_name');
        self::assertEquals('database_name.pages', $this->_object->getFullNameTable());
    }

    // tests

    public function testGenerateModelClass()
    {
        self::assertEquals($this->_object->generateModelClass('Notifications\Table'), 'Notifications\Row');
        self::assertEquals($this->_object->generateModelClass('Notifications_Tables'), 'Notifications_Table');
        self::assertEquals($this->_object->generateModelClass('Notifications'), 'Notification');
        self::assertEquals($this->_object->generateModelClass('Persons'), 'Person');
    }

    /**
     * @return array
     */
    public function providerGetController()
    {
        return [
            ["notifications-tables", "Notifications_Tables"],
            ["notifications-tables", "Notifications\\Tables\\Tables"],
            ["notifications-tables", "App\\Models\\Notifications\\Tables\\Tables"],
        ];
    }

    /**
     * @dataProvider providerGetController
     * @param $controller
     * @param $class
     */
    public function testGetController($controller, $class)
    {
        /** @var Records $records */
        $records = new Records();
        $records->setClassName($class);

        self::assertEquals($controller, $records->getController());
    }

//    public function testInitRelationsFromArrayBelongsToSimple()
//    {
    /** @var Records $users */
//        $users = m::namedMock('Users', Records::class)->shouldDeferMissing()
//            ->shouldReceive('instance')->andReturnSelf()
//            ->getMock();

//        $users->setPrimaryFK('id_user');
//
//        m::namedMock('User', Records::class);
//        m::namedMock('Articles', Records::class);

//        $this->_object->setPrimaryFK('id_object');
//
//        $this->_object->initRelationsFromArray('belongsTo', ['User']);
//        $this->_testInitRelationsFromArrayBelongsToUser('User');
//
//        $this->_object->initRelationsFromArray('belongsTo', [
//            'UserName' => ['with' => $users],
//        ]);
//        $this->_testInitRelationsFromArrayBelongsToUser('UserName');
//
//        self::assertSame($users, $this->_object->getRelation('User')->getWith());
//    }

    public function testNewCollection()
    {
        $collection = $this->_object->newCollection();
        self::assertInstanceOf('Nip\Records\Collections\Collection', $collection);
        self::assertSame($this->_object, $collection->getManager());
    }

    public function testRequestFilters()
    {
        $request = new Request();
        $params = [
            'title' => 'Test title',
            'name' => 'Test name',
        ];
        $request->query->add($params);

        $this->_object->getFilterManager()->addFilter(
            $this->_object->getFilterManager()->newFilter('Column\BasicFilter')
                ->setField('title')
        );

        $this->_object->getFilterManager()->addFilter(
            $this->_object->getFilterManager()->newFilter('Column\BasicFilter')
                ->setField('name')
        );

        $filtersArray = $this->_object->requestFilters($request);
        self::assertSame($filtersArray, $params);
    }

    /**
     * @return array
     */
    public function providerGetPrimaryFK()
    {
        return [
            ["id_user", "Users"],
            ["id_race_entry", "RaceEntries"],
            ["id_notifications_table", "Notifications_Tables"],
            ["id_notifications_table", "Notifications\\Tables\\Tables"],
            ["id_notifications_table", "App\\Models\\Notifications\\Tables\\Tables"],
        ];
    }

    /**
     * @dataProvider providerGetPrimaryFK
     * @param $primaryFK
     * @param $class
     */
    public function testGetPrimaryFK($primaryFK, $class)
    {
        /** @var Records $records */
//        $records = m::namedMock($class, 'Records')->shouldDeferMissing()
//            ->shouldReceive('instance')->andReturnSelf()
//            ->shouldReceive('getPrimaryKey')->andReturn('id')
//            ->getMock();
        $records = new Records();
        $records->setClassName($class);
        $records->setPrimaryKey('id');

        self::assertEquals($primaryFK, $records->getPrimaryFK());
    }

    public function testGetPrimaryKey()
    {
        $records = new Records();
        $tableStructure = unserialize(file_get_contents(TEST_FIXTURE_PATH . '/database_structure/users.serialize'));
        $records->setTableStructure($tableStructure);
        $records->setPrimaryKey('id');

        self::assertEquals('id', $records->getPrimaryKey());
    }

    public function testGetCollectionClass()
    {
        self::assertEquals(Collection::class, $this->_object->getCollectionClass());
    }

    public function testGetUrlHelper()
    {
        $records = new Records();
        $urlHelper = $records->Url();
        self::assertInstanceOf(Nip_Helper_Url::class, $urlHelper);
    }

    protected function setUp()
    {
        parent::setUp();

        $wrapper = new Connection(null);

        $this->_object = m::mock(Records::class)->shouldDeferMissing()
            ->shouldReceive('getRequest')->andReturn(Request::create('/'))
            ->getMock();

        $this->_object->setDB($wrapper);
        $this->_object->setTable('pages');
    }
}
