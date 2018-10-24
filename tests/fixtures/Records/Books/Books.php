<?php

namespace Nip\Records\Tests\Fixtures\Records\Books;

/**
 * Class Books
 * @package Nip\Records\Tests\Fixtures\Records\Books
 */
class Books extends \Nip\Records\RecordManager
{
    protected function generateTable()
    {
        return 'books';
    }

    public function generatePrimaryFK()
    {
        return 'id_book';
    }

    public function generatePrimaryKey()
    {
        return 'id';
    }
}
