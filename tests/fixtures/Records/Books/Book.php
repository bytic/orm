<?php

namespace Nip\Records\Tests\Fixtures\Records\Books;

/**
 * Class Books
 * @package Nip\Records\Tests\Fixtures\Records\Books
 *
 * @property string name
 */
class Book extends \Nip\Records\Record
{

    /**
     * @param $value
     */
    public function setName($value)
    {
        $this->setDataValue('name', $value);
    }

    /**
     * @param $value
     */
    public function setTitleAttribute($value)
    {
        $this->setDataValue('title', strtoupper($value));
    }
}