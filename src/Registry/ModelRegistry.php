<?php

namespace Nip\Records\Registry;

use Nip\Collections\Lazy\AbstractLazyCollection;
use Nip\Records\Registry\Traits\IsCachedTrait;

/**
 * Class ModelRegistry
 * @package Nip\Records\Registry
 */
class ModelRegistry extends AbstractLazyCollection
{
    use IsCachedTrait;
}
