<?php

namespace Nip\Records\Traits\HasUuid;


use Exception;
use Nip\Utility\Uuid;

/**
 * Trait HasUuidRecordManagerTrait
 * @package Nip\Records\Traits\HasUuid
 */
trait HasUuidRecordManagerTrait
{
    /**
     * The UUID versions.
     *
     * @var array
     */
    protected $uuidVersions = [
        '1',
        '3',
        '4',
        '5',
        '6',
        'ordered',
    ];

    public function bootHasUuidRecordManagerTrait()
    {

    }

    /**
     * The name of the column that should be used for the UUID.
     *
     * @return string
     */
    public function uuidColumn(): string
    {
        return 'uuid';
    }

    /**
     * Resolve the UUID version to use when setting the UUID value. Default to uuid4.
     *
     * @return string
     */
    public function resolveUuidVersion(): string
    {
        if (property_exists($this, 'uuidVersion') && in_array($this->uuidVersion, $this->uuidVersions)) {
            return $this->uuidVersion;
        }
        if (method_exists($this, 'uuidVersion')) {
            $version = $this->uuidVersion();
            if (in_array($version, $this->uuidVersions)) {
                return $version;
            }
        }

        return 4;
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function generateUuid(): string
    {
        $version = $this->resolveUuidVersion();

        switch ($version) {
            case 4:
                return Uuid::v4()->toString();
        }

        throw new Exception("UUID version [{$version}] not supported.");
    }
}