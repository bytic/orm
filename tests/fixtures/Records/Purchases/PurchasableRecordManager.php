<?php

namespace Nip\Records\Tests\Fixtures\Records\Purchases;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Utility\Traits\SingletonTrait;

/**
 * Class PurchasableRecordManager
 * @package Nip\Records\Tests\Fixtures\Records\Purchases
 */
class PurchasableRecordManager extends RecordManager
{
    use SingletonTrait;

    protected $primaryKey = 'id';

    public function getPaymentsUrlPK()
    {
        return 'id';
    }

    protected function generateTableStructure()
    {
        return [
            'fields' => []
        ];
    }
}
