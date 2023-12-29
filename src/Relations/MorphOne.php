<?php

namespace Nip\Records\Relations;

use Nip\Database\Query\AbstractQuery;
use Nip\Records\Relations\Traits\HasMorphTypeTrait;

class MorphOne extends MorphOneOrMany
{

    use HasMorphTypeTrait;

    public function initResults()
    {
        $withManager = $this->getWith();
        if (false == $this->isPopulatable()) {
            return $this->setResults(false);
        }
        $query = $this->getQuery();
        $results = $withManager->findByQuery($query);

        if (count($results) < 1) {
            return $this->setResults(false);
        }
        $this->setResults($results->rewind());
    }

    /**
     * @param AbstractQuery $query
     */
    public function populateQuerySpecific(AbstractQuery $query)
    {
        $query->where($this->getMorphTypeField() . ' = ?', $this->getMorphValue());
        $query->where('`' . $this->getFK() . '` = ?', $this->getItemRelationPrimaryKey());
    }
}