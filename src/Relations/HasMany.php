<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;
use Nip\Database\Query\Select as Query;

/**
 * Class HasMany
 * @package Nip\Records\Relations
 */
class HasMany extends HasOneOrMany
{

    /**
     * @inheritdoc
     */
    public function populateQuerySpecific(AbstractQuery $query)
    {
        $primaryKey = $this->getManager()->getPrimaryKey();
        $query->where('`' . $this->getFK() . '` = ?', $this->getItem()->{$primaryKey});

        return $query;
    }
}
