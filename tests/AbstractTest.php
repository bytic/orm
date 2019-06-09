<?php

namespace Nip\Records\Tests;

use PHPUnit\Framework\TestCase;
use \Mockery as m;

/**
 * Class AbstractTest
 */
abstract class AbstractTest extends TestCase
{
    protected $object;


    protected function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}
