<?php

use Nip\Container\Container;
use Nip\Database\Connections\Connection;
use Mockery as m;

require dirname(__DIR__) . '/vendor/autoload.php';

define('PROJECT_BASE_PATH', __DIR__ . '/..');
define('TEST_BASE_PATH', __DIR__);
define('TEST_FIXTURE_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'fixtures');

$connection = new Connection(false);
$adapter = m::namedMock('TestAdapter', \Nip\Database\Adapters\MySQLi::class)->makePartial()
    ->shouldReceive('query')->andReturn(true)
    ->shouldReceive('lastInsertID')->andReturn(99)
    ->shouldReceive('cleanData')->andReturnUsing(function ($arg) {
        return $arg;
    })
    ->getMock();
$connection->setAdapter($adapter);

Container::setInstance(new Container());
Container::getInstance()->set('db.connection', $connection);

Container::getInstance()->set('inflector', new \Nip\Inflector\Inflector());
