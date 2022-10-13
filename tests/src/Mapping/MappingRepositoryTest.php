<?php

declare(strict_types=1);

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
        $repository = new MappingRepository();
        $repository->initFromCache($cache);

        $data = $repository->get('test');
        self::assertInstanceOf(MappingData::class, $data);
        self::assertSame('test', $data->getTable());
    }
}
