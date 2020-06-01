<?php

namespace Nip\Records\Traits\HasUuid;


use Exception;
use Nip\Records\EventManager\Events\Event;
use Nip\Records\RecordManager;
use Nip\Utility\Uuid;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

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
        static::creating(function (Event $event) {
            $record = $event->getRecord();
            /** @var static|RecordManager $manager */
            $manager = $event->getManager();
            $columns = $manager->uuidColumns();
            foreach ($columns as $column) {
                /* @var \Ramsey\Uuid\Uuid $uuid */
                $uuid = $manager->generateUuid();

                if (isset($record->{$column}) && !is_null($record->{$column})) {
                    try {
                        $uuid = $uuid->fromString(strtolower($record->{$column}));
                    } catch (InvalidUuidStringException $e) {
                        $uuid = $uuid->fromBytes($record->{$column});
                    }
                }

                $record->{$column} = strtolower($uuid->toString());
            }
        });
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
     * The names of the columns that should be used for the UUID.
     *
     * @return array
     */
    public function uuidColumns(): array
    {
        return [$this->uuidColumn()];
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
     * @return \Ramsey\Uuid\UuidInterface
     * @throws Exception
     */
    protected function generateUuid()
    {
        $version = $this->resolveUuidVersion();

        switch ($version) {
            case 4:
                return Uuid::v4();
        }

        throw new Exception("UUID version [{$version}] not supported.");
    }
}