<?php

namespace Nip\Records;

use Nip\Records\Instantiator\CanInstanceTrait;
use Nip\Records\Traits\HasFilters\RecordsTrait as HasFilters;
use Nip\Records\Traits\HasMorphName\HasMorphNameManagerTrait;
use Nip\Records\Traits\Relations\HasRelationsRecordsTrait;

/**
 * Class RecordManager
 * @package Nip\Records
 */
class RecordManager extends AbstractModels\RecordManager
{
    use HasFilters;
    use HasRelationsRecordsTrait;
    use HasMorphNameManagerTrait;
    use CanInstanceTrait;
}
