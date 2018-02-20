<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;

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

        $pk = $this->getManager()->getPrimaryKey();
        $query->where('`' . $this->getFK() . '` = ?', $this->getItem()->{$pk});
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
}
