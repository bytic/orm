<?php

namespace Nip\Records\Tests\Registry\Traits;

use Nip\Records\Registry\ModelRegistry;
use Nip\Records\Tests\AbstractTest;

/**
 * Class IsCachedTraitTest
 * @package Nip\Records\Tests\Registry\Traits
 */
class IsCachedTraitTest extends AbstractTest
{
    public function test_destruct()
    {
        $registry = \Mockery::mock(ModelRegistry::class)->shouldAllowMockingProtectedMethods()->makePartial();
//        $registry->shouldReceive('checkSaveCache')->once();

        self::assertInstanceOf(ModelRegistry::class, $registry);

//        unset($registry);
    }
}
