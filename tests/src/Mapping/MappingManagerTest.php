<?php

namespace Nip\Records\Tests\Mapping;

use Mockery\Mock;
use Nip\Records\Mapping\MappingManager;
use Nip\Records\Mapping\MappingRepository;
use Nip\Records\Tests\AbstractTest;

/**
 * Class MappingManagerTest
 * @package Nip\Records\Tests\Mapping
 */
class MappingManagerTest extends AbstractTest
{
    public function test_bootIfNeeded()
    {
        /** @var MappingManager|Mock $manager */
        $manager = \Mockery::mock(MappingManager::class)->shouldAllowMockingProtectedMethods()->makePartial();
        $manager->shouldReceive('boot')->once();

        $manager->__construct();

        $manager->repository();
        $manager->repository();
        self::assertInstanceOf(MappingRepository::class,$manager->repository());
    }
}