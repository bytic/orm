<?php

namespace Nip\Records\Traits\HasDatabase;

use Nip\Container\Container;
use Nip\Database\Connections\Connection;

/**
 * Trait HasDatabaseRecordsTrait
 * @package Nip\Records\Traits\HasDatabase
 */
trait HasDatabaseRecordsTrait
{

    /**
     * @var Connection
     */
    protected $connection = null;

    /**
     * @return Connection
     */
    public function getDB()
    {
        if ($this->connection == null) {
            $this->initDB();
        }

        $this->checkDB();

        return $this->connection;
    }

    protected function initDB()
    {
        $this->setDB($this->newDbConnection());
    }

    /**
     * @param Connection $connection
     * @return $this
     */
    public function setDB($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return Connection
     */
    protected function newDbConnection()
    {
        if (function_exists('app')) {
            return app('db.connection');
        }
        return Container::getInstance()->get('db.connection');
    }

    public function checkDB()
    {
        if (!$this->hasDB()) {
            trigger_error("Database connection missing for [" . get_class($this) . "]", E_USER_ERROR);
        }
    }

    /**
     * @return bool
     */
    public function hasDB()
    {
        return $this->connection instanceof Connection;
    }
}
