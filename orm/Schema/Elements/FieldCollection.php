<?php


declare(strict_types=1);

namespace ByTIC\ORM\Schema\Elements;

use ByTIC\ORM\Exception\SchemaException;
use Nip\Collections\Typed\ClassCollection;

/**
 * Class FieldCollection
 * @package ByTIC\ORM\Schema\Elements
 */
class FieldCollection extends ClassCollection
{
    protected $validClass = FieldSchema::class;

    /**
     * Checks if the field is unique.
     *
     * @param string $fieldName The field name.
     *
     * @return boolean TRUE if the field is unique, FALSE otherwise.
     */
    public function isUniqueField($fieldName)
    {
        return $this->getFieldSchema($fieldName)->isUnique();
    }

    /**
     * Checks if the field is not null.
     *
     * @param string $fieldName The field name.
     *
     * @return boolean TRUE if the field is not null, FALSE otherwise.
     */
    public function isNullable($fieldName)
    {
        return $this->getFieldSchema($fieldName)->isNullable();
    }

    /**
     * Gets the mapping of a (regular) field that holds some data but not a
     * reference to another object.
     *
     * @param string $name The field name.
     *
     * @return FieldSchema The field mapping.
     *
     * @throws SchemaException
     */
    public function getFieldSchema(string $name): FieldSchema
    {
        if ($this->has($name)) {
            throw new SchemaException("Invalid field name `$name`");
        }

        return $this->get($name);
    }
}
