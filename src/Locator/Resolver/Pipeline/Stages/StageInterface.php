<?php

namespace Nip\Records\Locator\Resolver\Pipeline\Stages;

use Nip\Records\Locator\Resolver\Commands\Command;

/**
 * Interface StageInterface
 * @package Nip\Records\Locator\Resolver\Pipeline\Stages
 */
interface StageInterface
{
    /**
     * @param Command $methodCall
     * @return Command
     */
    public function __invoke(Command $methodCall): Command;
}
