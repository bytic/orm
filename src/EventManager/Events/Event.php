<?php

namespace Nip\Records\EventManager\Events;

use Nip\Records\AbstractModels\Record;
use Nip\Records\AbstractModels\RecordManager;

/**
 * Class Event
 * @package Nip\Records\EventManager\Events
 */
class Event
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var RecordManager
     */
    protected $manager;

    /**
     * @var Record
     */
    protected $record;

    /**
     * Event constructor.
     * @param string $name
     * @param RecordManager $manager
     * @param Record $record
     */
    public function __construct(string $name, RecordManager $manager, Record $record)
    {
        $this->name = $name;
        $this->manager = $manager;
        $this->record = $record;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return RecordManager
     */
    public function getManager(): RecordManager
    {
        return $this->manager;
    }

    /**
     * @return Record
     */
    public function getRecord(): Record
    {
        return $this->record;
    }
}