<?php

namespace Nip\Records\Tests;

use Mockery as m;
use Nip\Records\Record;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTest
 */
abstract class AbstractTest extends TestCase
{
    /**
     * @var Record
     */
    protected $object;

    protected function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }
}
