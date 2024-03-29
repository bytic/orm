<?php

namespace Nip\Records\Traits\ActiveRecord;

use Nip\Database\Query\AbstractQuery as Query;
use Nip\Database\Query\Delete as DeleteQuery;
use Nip\Database\Query\Insert as InsertQuery;
use Nip\Database\Query\Select as SelectQuery;
use Nip\Database\Query\Update as UpdateQuery;
use Nip\Database\Result;
use Nip\Records\AbstractModels\Record;
use Nip\Records\Collections\Collection as RecordCollection;
use Nip\Records\EventManager\Events\Observe;
use Nip\Records\Traits\HasDatabase\HasDatabaseRecordsTrait;
use Nip\Records\Traits\HasForeignKey\RecordsTrait as HasForeignKeyTrait;
use Nip\Records\Traits\HasPrimaryKey\RecordsTrait as HasPrimaryKeyTrait;
use Nip\Records\Traits\Searchable\SearchableRecordsTrait;
use Nip\Records\Traits\TableStructure\TableStructureRecordsTrait;
use Nip\Records\Traits\Unique\RecordsTrait as UniqueRecordsTrait;

/**
 * Class ActiveRecordsTrait
 * @package Nip\Records\Traits\ActiveRecord
 */
trait ActiveRecordsTrait
{
    use UniqueRecordsTrait;
    use HasForeignKeyTrait;
    use HasPrimaryKeyTrait;
    use SearchableRecordsTrait;
    use TableStructureRecordsTrait;
    use HasDatabaseRecordsTrait;

    /**
     * @var null|string
     */
    protected $table = null;

    /**
     * @return SelectQuery
     */
    public function newSelectQuery()
    {
        return $this->newQuery('select');
    }

    /**
     * Factory
     * @param string $type
     * @return Query|SelectQuery|InsertQuery|DeleteQuery|UpdateQuery
     */
    public function newQuery($type = 'select')
    {
        $query = $this->getDB()->newQuery($type);
        $query->cols("`" . $this->getTable() . "`.*");
        $query->from($this->getFullNameTable());
        $query->table($this->getTable());

        return $query;
    }

    /**
     * @return string
     */
    public function getTable()
    {
        if ($this->table === null) {
            $this->initTable();
        }

        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }

    protected function initTable()
    {
        $this->setTable($this->generateTable());
    }

    /**
     * @return string
     */
    protected function generateTable()
    {
        return str_replace('-', '_', $this->getController());
    }

    /**
     * @return string
     */
    public function getFullNameTable()
    {
        $database = $this->getDB()->getDatabase();

        return $database ? $database . '.' . $this->getTable() : $this->getTable();
    }

    /**
     * Inserts a Record into the database
     * @param Record $model
     * @param array|bool $onDuplicate
     * @return integer
     */
    public function insert($model, $onDuplicate = false)
    {
        if ($this->fireModelEvent(Observe::CREATING, $model) === false) {
            return false;
        }
        $query = $this->insertQuery($model, $onDuplicate);
        $return = $this->performInsert($query, $model);

        $this->fireModelEvent(Observe::CREATED, $model);
        return $return;
    }

    /**
     * @param Record $model
     * @param $onDuplicate
     * @return InsertQuery
     */
    public function insertQuery($model, $onDuplicate)
    {
        $inserts = $this->getQueryModelData($model);

        $query = $this->newInsertQuery();
        $query->data($inserts);

        if ($onDuplicate !== false) {
            $query->onDuplicate($onDuplicate);
        }

        return $query;
    }

    /**
     * @return InsertQuery
     */
    public function newInsertQuery()
    {
        return $this->newQuery('insert');
    }

    /**
     * @param Query $query
     * @param Record $record
     * @return bool
     */
    protected function performInsert(Query $query, Record $record)
    {
        $pk = $this->getPrimaryKey();
        $query->execute();
        $lastId = $this->getDB()->lastInsertID();

        if ($pk == 'id') {
            $record->{$pk} = $lastId;
        }
        $record->syncOriginal();
        return true;
    }

    /**
     * Updates a Record's database entry
     * @param Record $model
     * @return bool|Result
     */
    public function update(Record $model)
    {
        // If the "UPDATING" event returns false we'll bail out of the save and return
        // false, indicating that the save failed. This provides a chance for any
        // listeners to cancel save operations if validations fail or whatever.
        if ($this->fireModelEvent(Observe::UPDATING, $model) === false) {
            return false;
        }

        $query = $this->updateQuery($model);
        if (!is_object($query)) {
            return false;
        }
        $return = $this->performUpdate($query, $model);
        $this->fireModelEvent(Observe::UPDATED, $model);
        return $return;
    }

    /**
     * @param Record $model
     * @return bool|UpdateQuery
     */
    public function updateQuery(Record $model)
    {
        $pk = $this->getPrimaryKey();
        if (!is_array($pk)) {
            $pk = [$pk];
        }

        $data = $this->getQueryModelData($model);

        if ($data) {
            $query = $this->newUpdateQuery();
            $query->data($data);

            foreach ($pk as $key) {
                $query->where("$key = ?", $model->{$key});
            }

            return $query;
        }

        return false;
    }


    /**
     * @return UpdateQuery
     */
    public function newUpdateQuery()
    {
        return $this->newQuery('update');
    }


    /**
     * @param Query $query
     * @param Record $record
     * @return bool
     */
    protected function performUpdate(Query $query, Record $record)
    {
        $query->execute();
        $record->syncOriginal();
        return true;
    }

    /**
     * Saves a Record's database entry
     * @param Record $model
     * @return mixed
     */
    public function save(Record $model)
    {
        if ($model->hasPrimaryKey()) {
            $model->update();

            return $model->getPrimaryKey();
        }

        /** @var Record $previous */
        $previous = $model->exists();
        if ($previous) {
            $data = $model->toArray();

            if ($data) {
                $previous->fill($model->toArray());
            }
            $previous->update();

            $model->fill($previous->toArray());

            return $model->getPrimaryKey();
        }

        $model->insert();
        return $model->getPrimaryKey();
    }

    /**
     * Delete a Record's database entry
     *
     * @param Record $model
     * @return false
     */
    public function delete($model)
    {
        if ($this->fireModelEvent(Observe::DELETING, $model) === false) {
            return false;
        }
        $pk = $this->getPrimaryKey();

        if ($model instanceof $this->model) {
            $primary = $model->getPrimaryKey();
        } else {
            $primary = $model;
        }

        $query = $this->newDeleteQuery();
        $query->where("$pk = ?", $primary);
        $query->limit(1);

        $this->getDB()->execute($query);
        $this->fireModelEvent(Observe::DELETED, $model);
        return true;
    }

    /**
     * @return DeleteQuery
     */
    public function newDeleteQuery()
    {
        return $this->newQuery('delete');
    }

    /**
     * Delete a Record's database entry
     * @param array $params
     * @return $this
     */
    public function deleteByParams($params = [])
    {
        extract($params);

        $query = $this->newDeleteQuery();

        if (isset($where)) {
            if (is_array($where)) {
                foreach ($where as $condition) {
                    $condition = (array)$condition;
                    $query->where($condition[0], $condition[1]);
                }
            } else {
                call_user_func_array([$query, 'where'], $where);
            }
        }

        if (isset($order)) {
            call_user_func_array([$query, 'order'], $order);
        }

        if (isset($limit)) {
            call_user_func_array([$query, 'limit'], $limit);
        }

        $this->getDB()->execute($query);

        return $this;
    }

    /**
     * @param array $params
     * @return SelectQuery
     */
    public function paramsToQuery($params = [])
    {
        $this->injectParams($params);

        $query = $this->newQuery('select');
        $query->addParams($params);

        return $query;
    }

    /**
     * @param bool|array $where
     * @return int
     */
    public function count($where = false)
    {
        return $this->countByParams(["where" => $where]);
    }

    /**
     * Counts all the Record entries in the database
     * @param array $params
     * @return int
     */
    public function countByParams($params = [])
    {
        $this->injectParams($params);
        $query = $this->newQuery('select');
        $query->addParams($params);

        return $this->countByQuery($query);
    }

    /**
     * Counts all the Record entries in the database
     * @param Query $query
     * @return int
     */
    public function countByQuery($query)
    {
        $queryCount = clone $query;
        $queryCount->setCols('count(*) as count');
        $result = $this->getDB()->execute($queryCount);

        if ($result->numRows()) {
            $row = $result->fetchResult();

            return (int)$row['count'];
        }

        return false;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function cleanData($data)
    {
        return $this->getDB()->getAdapter()->cleanData($data);
    }

    /**
     * @param Record $model
     * @return array
     */
    public function getQueryModelData($model)
    {
        $fields = $this->getFields();
        $data = $model->getDirty($fields);
        foreach ($data as $field=>$value) {
            if (empty($value)) {
                $data[$field] = $this->isNullable($field) || $this->isAutoincrement($field) ? null : '';
            }
        }
        return $data;
    }

    /**
     * @param string $name
     * @param $arguments
     * @return RecordCollection|false
     */
    protected function isCallDatabaseOperation($name, $arguments)
    {
        $operations = ["find", "delete", "count"];
        foreach ($operations as $operation) {
            if (strpos($name, $operation . "By") !== false || strpos($name, $operation . "OneBy") !== false) {
                $match = str_replace([$operation . "By", $operation . "OneBy"], "", $name);
                $field = inflector()->underscore($match);

                if ($this->hasField($field) === false) {
                    continue;
                }

                $params = [];
                if (count($arguments) > 1 && is_array(end($arguments))) {
                    $params = end($arguments);
                }

                if ($field == $this->getPrimaryKey()) {
                    return $this->findByPrimary($arguments[0]);
                }

                $params['where'][] = ["$field " . (is_array($arguments[0]) ? "IN" : "=") . " ?", $arguments[0]];

                $operation = str_replace($match, "", $name) . "Params";

                $results = $this->$operation($params);
                // RETURN NULL TO DISTINCT FROM FALSE THAT MEANS NOT A DATABASE OPERATION
                return ($results) ? $results : null;
            }
        }

        return false;
    }
}
