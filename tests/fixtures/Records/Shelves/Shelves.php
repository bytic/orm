<?php

namespace Nip\Records\Tests\Fixtures\Records\Shelves;

/**
 * Class Shelves
 * @package Nip\Records\Tests\Fixtures\Records\Shelves
 */
class Shelves extends \Nip\Records\RecordManager
{

    protected function generateTable()
    {
        return 'shelves';
    }

    public function generatePrimaryFK()
    {
        return 'id_shelf';
    }

    public function generatePrimaryKey()
    {
        return 'id';
    }
}
