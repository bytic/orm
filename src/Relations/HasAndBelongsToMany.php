<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;
use Nip\Database\Query\Delete as DeleteQuery;
use Nip\Database\Query\Insert as InsertQuery;
use Nip\Database\Query\Select as SelectQuery;
use Nip\HelperBroker;
use Nip\Records\Collections\Collection as RecordCollection;
use Nip\Records\Record;
use Nip\Records\Relations\Traits\HasPivotTable;

/**
 * Class HasAndBelongsToMany
 * @package Nip\Records\Relations
 */
class HasAndBelongsToMany extends HasOneOrMany
{
    use HasPivotTable;

    /**
     * @var string
     */
    protected $type = 'hasAndBelongsToMany';

    /**
     * @var null
     */
    protected $joinFields = null;

    /**
     * @inheritdoc
     */
    public function addParams($params)
    {
        parent::addParams($params);
        $this->addPivotParams($params);
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return SelectQuery
     * @throws \Exception
     */
    public function newQuery()
    {
        $query = $this->getDB()->newSelect();

        $query->from($this->getWith()->getFullNameTable());
        $query->from($this->getDB()->getDatabase() . '.' . $this->getTable());

        foreach ($this->getWith()->getFields() as $field) {
            $query->cols(["{$this->getWith()->getTable()}.$field", $field]);
        }

        foreach ($this->getJoinFields() as $field) {
            $query->cols(["{$this->getTable()}.$field", "__$field"]);
        }

        $this->hydrateQueryWithPivotConstraints($query);

        $order = $this->getParam('order');
        if (is_array($order)) {
            foreach ($order as $item) {
                $query->order([$item[0], $item[1]]);
            }
        }

        return $query;
    }

    /**
     * @return null|array
     */
    protected function getJoinFields()
    {
        if ($this->joinFields == null) {
            $this->initJoinFields();
        }

        return $this->joinFields;
    }

    /**
     * @param null $joinFields
     */
    public function setJoinFields($joinFields)
    {
        $this->joinFields = $joinFields;
    }

    protected function initJoinFields()
    {
        $structure = $this->getDB()->getMetadata()->describeTable($this->getTable());
        $this->setJoinFields(array_keys($structure["fields"]));
    }

    /**
     * @inheritdoc
     */
    public function populateQuerySpecific(AbstractQuery $query)
    {
        $pk1 = $this->getPrimaryKey();
        $fk1 = $this->getFK();

        $query->where("`{$this->getTable()}`.`$fk1` = ?", $this->getItem()->{$pk1});

        return $query;
    }

    /**
     * Simple select query from the link table
     * @param bool $specific
     * @return SelectQuery
     */
    public function getLinkQuery($specific = true)
    {
        $query = $this->getDB()->newSelect();
        $query->from($this->getTable());

        if ($specific) {
            $query = $this->populateQuerySpecific($query);
        }

        return $query;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @param RecordCollection $collection
     * @return RecordCollection
     */
    public function getEagerResults($collection)
    {
        if ($collection->count() < 1) {
            return $this->newCollection();
        }

        $query = $this->getEagerQuery($collection);

        $return = $this->newAssociatedCollection();
        $results = $this->getDB()->execute($query);
        if ($results->numRows() > 0) {
            $i = 1;
            $rows = $results->fetchResults();
            foreach ($rows as $row) {
                $row['relation_key'] = $i++;
                $item = $this->getWith()->getNew($row);
                $return->add($item, 'relation_key');
            }
        }

        return $return;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return $this
     */
    public function save()
    {
        $this->deleteConnections();
        $this->saveConnections();

        return $this;
    }

    protected function deleteConnections()
    {
        $query = $this->newDeleteQuery();
        $query->where(
            "{$this->getFK()} = ?",
            $this->getItemRelationPrimaryKey()
        );
//        echo $query;
        $query->execute();
    }

    /**
     * @return DeleteQuery
     */
    protected function newDeleteQuery()
    {
        $query = $this->getDB()->newDelete();
        $query->table($this->getTable());
        return $query;
    }

    protected function saveConnections()
    {
        if ($this->hasResults()) {
            $query = $this->newInsertQuery();
            $this->queryAttachRecords($query, $this->getResults());
//            echo $query;
            $query->execute();
        }
    }

    /**
     * @return InsertQuery
     */
    protected function newInsertQuery()
    {
        $query = $this->getDB()->newInsert();
        $query->table($this->getTable());
        return $query;
    }

    /**
     * @param InsertQuery $query
     * @param $records
     */
    protected function queryAttachRecords($query, $records)
    {
        foreach ($records as $record) {
            $data = $this->formatAttachData($record);
            foreach ($this->getJoinFields() as $field) {
                if ($record->{"__$field"}) {
                    $data[$field] = $record->{"__$field"};
                } else {
                    $data[$field] = isset($data[$field]) ? $data[$field] : false;
                }
            }
            $query->data($data);
        }
    }

    /**
     * @param $record
     * @return array
     */
    protected function formatAttachData($record): array
    {
        return [
            $this->getFK() => $this->getItem()->{$this->getManager()->getPrimaryKey()},
            $this->getPivotFK() => $record->{$this->getWith()->getPrimaryKey()},
        ];
    }

    /**
     * @param $model
     */
    public function attach($model)
    {
        $query = $this->newInsertQuery();
        $this->queryAttachRecords($query, [$model]);
        $query->execute();
    }

    /**
     * @param Record $model
     */
    public function detach($model)
    {
        $query = $this->newDeleteQuery();
        $this->queryDetachRecords($query, [$model]);
        $query->execute();
    }

    /**
     * @param DeleteQuery $query
     * @param $records
     */
    protected function queryDetachRecords($query, $records)
    {
        $ids = HelperBroker::get('Arrays')->pluck($records, $this->getWith()->getPrimaryKey());
        $query->where(
            "{$this->getPivotFK()} IN ?",
            $ids
        );

        $query->where(
            "{$this->getFK()} = ?",
            $this->getItemRelationPrimaryKey()
        );
    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @noinspection PhpDocMissingThrowsInspection
     * @return mixed
     */
    public function getWithClass()
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getName();
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return string
     */
    protected function getDictionaryKey()
    {
        return '__' . $this->getFK();
    }
}
