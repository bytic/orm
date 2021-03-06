<?php

namespace Nip\Records\Tests\Mapping;

use Nip\Records\Mapping\MappingData;
use Nip\Records\Mapping\MappingRepository;
use Nip\Records\Tests\AbstractTest;

/**
 * Class MappingRepositoryTest
 * @package Nip\Records\Tests\Mapping
 */
class MappingRepositoryTest extends AbstractTest
{
    public function test_generateCache()
    {
        $repository = new MappingRepository();
        $data = new MappingData();
        $data->setTable('test');
        $repository->set('test', $data);

        $cache = $repository->generateCache();
        self::assertSame(
            'a:1:{s:4:"test";C:31:"Nip\Records\Mapping\MappingData":122:{a:6:{s:5:"table";s:4:"test";s:5:"model";N;s:10:"controller";N;s:14:"tableStructure";N;s:6:"fields";N;s:10:"bootTraits";N;}}}',
            $cache
        );
    }
}
