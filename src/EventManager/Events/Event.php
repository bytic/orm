<?php

namespace Nip\Records\EventManager\Events;

use Nip\Records\AbstractModels\Record;
use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\EventManager\EventManager;

/**
 * Class Event
 * @package Nip\Records\EventManager\Events
 */
class Event
{
    /**
     * @var string
     */
    protected $stage;

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
     * @param string $stage
     * @param RecordManager $manager
     * @param Record $record
     */
    public function __construct(string $stage, RecordManager $manager, Record $record = null)
    {
        $this->stage = $stage;
        $this->manager = $manager;
        if ($record instanceof Record) {
            $this->withRecord($record);
        }
    }

    /**
     * @param string $name
     * @param RecordManager $manager
     * @return Event
     */
    public static function create(string $name, RecordManager $manager)
    {
        return (new self($name, $manager));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return EventManager::eventName($this->getStage(), $this->getManager());
    }

    /**
     * @return string
     */
    public function getStage(): string
    {
        return $this->stage;
    }

    /**
     * @return RecordManager
     */
    public function getManager(): RecordManager
    {
        return $this->manager;
    }

    /**
     * @param Record $record
     * @return Event
     */
    public function withRecord(Record $record)
    {
        $this->record = $record;
        return $this;
    }

    /**
     * @return Record
     */
    public function getRecord(): Record
    {
        return $this->record;
    }
}