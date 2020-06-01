<?php

namespace Nip\Records\Tests\Mapping;

use Nip\Records\Mapping\MappingData;
use Nip\Records\Tests\AbstractTest;

/**
 * Class MappingDataTest
 * @package Nip\Records\Tests\Mapping
 */
class MappingDataTest extends AbstractTest
{
    public function test_serialize()
    {
        $data = new MappingData();
        $data->setTable('test');
        $data->setController('test-controller');
        $data->setModel('test-mode');
        $data->setTableStructure(['test-structure']);
        $data->setFields(['test-field']);
        $data->setBootTraits(['test-boot']);
        
        $serialized = serialize($data);
        self::assertIsString($serialized);

        $data1 = unserialize($serialized);
        self::assertInstanceOf(MappingData::class, $data1);
        self::assertEquals($data, $data1);
    }
}