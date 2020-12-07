<?php

namespace Nip\Records\Tests\Fixtures\Records\Books;

use Nip\Records\Traits\HasUuid\HasUuidRecordManagerTrait;
use Nip\Utility\Traits\SingletonTrait;

/**
 * Class Books
 * @package Nip\Records\Tests\Fixtures\Records\Books
 */
class Books extends \Nip\Records\RecordManager
{
    use SingletonTrait;
    use HasUuidRecordManagerTrait;

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

    public function generateTableStructure()
    {
        return require TEST_FIXTURE_PATH . '/database_structure/table_with_unique.php';
    }

    public function getRootNamespace()
    {
        return 'Nip\Records\Tests\Fixtures\Records';
    }

    /**
     * @param $event
     * @param $record
     * @return mixed
     */
    public function triggerModelEvent($event, $record)
    {
        return $this->fireModelEvent($event, $record);
    }
}
