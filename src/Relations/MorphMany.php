<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;
use Nip\Records\AbstractModels\Record;

/**
 * Class MorphMany
 * @package Nip\Records\Relations
 */
class MorphMany extends MorphOneOrMany
{

    /**
     * @param AbstractQuery $query
     */
    public function populateQuerySpecific(AbstractQuery $query)
    {
        $query->where($this->getMorphTypeField() . ' = ?', $this->getMorphValue());

        $primaryKey = $this->getPrimaryKey();
        $query->where('`' . $this->getFK() . '` = ?', $this->getItem()->{$primaryKey});
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function populateEagerQueryFromFkList($query, $fkList)
    {
        $value = $this->hasManager() ? $this->getMorphValue() : '';
        $query->where($this->getMorphTypeField() . ' = ?', $value);
        return parent::populateEagerQueryFromFkList($query, $fkList);
    }

    /**
     * @param Record $item
     */
    public function saveResult(Record $item)
    {
        $value = $this->hasManager() ? $this->getMorphValue() : '';
        $item->{$this->getMorphTypeField()} = $value;
        return parent::saveResult($item);
    }
}
