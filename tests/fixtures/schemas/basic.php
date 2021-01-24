<?php

use ByTIC\DataObjects\Tests\Fixtures\Models\Books\Book;
use ByTIC\ORM\Schema\Elements\EntitySchema;
use Nip\Records\Tests\Fixtures\Records\Books\Books;

return [
    'books' => [
        EntitySchema::ENTITY => Book::class,
        EntitySchema::REPOSITORY => Books::class
    ]
];