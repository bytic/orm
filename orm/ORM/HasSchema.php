<?php

declare(strict_types=1);

namespace ByTIC\ORM\ORM;

use ByTIC\ORM\ORM;
use ByTIC\ORM\Schema\Schema;
use ByTIC\ORM\Schema\SchemaInterface;

/**
 * Trait HasSchema
 * @package ByTIC\ORM\ORM
 */
trait HasSchema
{
    /** @var SchemaInterface|null */
    protected $schema;

    public static function schema(): SchemaInterface
    {
        return self::instance()->getSchema();
    }

    /**
     * @return SchemaInterface
     */
    public function getSchema(): SchemaInterface
    {
        return $this->schema;
    }

    protected function initSchema()
    {
        $this->schema = $schema ?? new Schema([]);
    }

    /**
     * @inheritdoc
     */
    public function withSchema(SchemaInterface $schema): ORM
    {
        $orm = clone $this;
        $orm->schema = $schema;

        return $orm;
    }

    /**
     * @param SchemaInterface|null $schema
     */
    public function setSchema(?SchemaInterface $schema): void
    {
        $this->schema = $schema;
    }
}
