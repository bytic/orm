<?php

namespace Nip\Records\Tests\Locator\Resolver\Pipeline\Stages;

use Nip\Records\Locator\Resolver\Pipeline\Stages\NamespaceSearch;
use Nip\Records\Tests\AbstractTest;

/**
 * Class TableNameClassInstanceTest
 * @package Nip\Records\Tests\Locator\Resolver\Pipeline\Stages
 */
class NamespaceSearchTest extends AbstractTest
{
    /**
     * @dataProvider generateClassProvider
     * @param string $alias
     * @param array $variations
     */
    public function testBuildNamespaceClasses($alias, $variations)
    {
        $stage = new NamespaceSearch();
        self::assertEquals($variations, $stage->buildAliasVariations($alias));
    }

    /**
     * @return array
     */
    public function generateClassProvider()
    {
        return [
            [
                'Books\Books',
                ['Books\Books', 'Books\Books\Books', 'Books\Books\BooksBooks']
            ],
            [
                'race-entries',
                ['Race\Entries', 'Race\Entries\Entries', 'Race\Entries\RaceEntries', 'RaceEntries\RaceEntries']
            ],
            [
                'race_entries',
                ['RaceEntries', 'RaceEntries\RaceEntries']
            ],
            [
                'race_entries-logs',
                [
                    'RaceEntries\Logs',
                    'RaceEntries\Logs\Logs',
                    'RaceEntries\Logs\RaceEntriesLogs',
                    'RaceEntriesLogs\RaceEntriesLogs'
                ]
            ],
            [
                'races-waiting_entries',
                [
                    'Races\WaitingEntries',
                    'Races\WaitingEntries\WaitingEntries',
                    'Races\WaitingEntries\RacesWaitingEntries',
                    'RacesWaitingEntries\RacesWaitingEntries'
                ]
            ]
        ];
    }
}
