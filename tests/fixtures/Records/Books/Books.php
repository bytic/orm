<?php

namespace Nip\Records\Tests\Fixtures\Records\Books;

use Nip\Database\Connections\MySqlConnection;

/**
 * Class Books
 * @package Nip\Records\Tests\Fixtures\Records\Books
 */
class Books extends \Nip\Records\RecordManager
{

    protected function newDbConnection()
    {
        return new MySqlConnection(null);
    }

    protected function generateTable()
    {
        return 'books';
    }
}
