<?php

namespace Nip\Records\Tests\Filters;

use Nip\Database\Query\Select;
use Nip\Records\Filters\AbstractFilter;
use Nip\Records\Filters\FilterManager;
use Nip\Records\Filters\Sessions\Session;
use Nip\Records\Tests\AbstractTest;
use Nip\Request;

/**
 * Class FilterManagerTest
 * @package Nip\Records\Tests\Filters
 */
class FilterManagerTest extends AbstractTest
{
    public function testEmptyFilterQuery()
    {
        $manager = new FilterManager();

        $query = (new Select())->from('books');
        $query = $manager->filterQuery($query);

        self::assertSame('SELECT * FROM `books`', $query->getString());
    }

    public function testFiltersAutoInitFromRequest()
    {
        $manager = new FilterManager();

        $request = new Request();
        $request->query->set('type', 5);
        $manager->setRequest($request);

        $manager->addFilter(
            $manager->newFilter('Column\BasicFilter')
                ->setField('type')
                ->setDbName('type')
        );

        $filter = $manager->get('type');
        self::assertInstanceOf(AbstractFilter::class, $filter);

        self::assertSame(['type' => 5], $manager->getFiltersArray());

        $query = (new Select())->from('books');
        $query = $manager->filterQuery($query);

        self::assertSame('SELECT * FROM `books` WHERE type = 5', $query->getString());
    }

    public function testFiltersReInit()
    {
        $manager = new FilterManager();

        $request = new Request();
        $request->query->set('type', 'books');
        $request->query->set('status', 'active');
        $manager->setRequest($request);

        $manager->addFilter(
            $manager->newFilter('Column\BasicFilter')->setField('type')
        );

        $manager->addFilter(
            $manager->newFilter('Column\BasicFilter')->setField('status')
        );

        self::assertSame(['type' => 'books','status' => 'active'], $manager->getFiltersArray());

        $newData = ['type' => 'toys'];
        $session = $manager->createSession($newData);

        self::assertInstanceOf(Session::class, $session);
        self::assertSame($session, $manager->getSession($session->getName()));

        self::assertSame($newData, $session->getFiltersArray());
        self::assertSame(['type' => 'books','status' => 'active'], $manager->getFiltersArray());
    }
}
