<?php

namespace Nip\Records\Tests\Fixtures\Records\Books\Chapters;

/**
 * Class BooksChapters
 * @package Nip\Records\Tests\Fixtures\Records\Books\Chapters
 */
class BooksChapters extends \Nip\Records\RecordManager
{
    protected function generateTable()
    {
        return 'books_chapters';
    }

    public function generatePrimaryFK()
    {
        return 'id_book';
    }

    public function generatePrimaryKey()
    {
        return 'id';
    }

    public function generateTableStructure()
    {
        return require TEST_FIXTURE_PATH . '/database_structure/table_with_unique.php';
    }
}
