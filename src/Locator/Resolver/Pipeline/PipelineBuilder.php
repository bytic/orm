<?php

namespace Nip\Records\Locator\Resolver\Pipeline;

use League\Pipeline\InterruptibleProcessor;
use League\Pipeline\PipelineBuilder as AbstractBuilder;
use League\Pipeline\PipelineInterface;
use League\Pipeline\ProcessorInterface;
use Nip\Records\Locator\Resolver\Commands\Command;
use Nip\Records\Locator\Resolver\Pipeline\Stages\ClassInstance;
use Nip\Records\Locator\Resolver\Pipeline\Stages\DefaultClassInstance;
use Nip\Records\Locator\Resolver\Pipeline\Stages\NamespaceSearch;
use Nip\Records\Locator\Resolver\Pipeline\Stages\RegistryInstance;
use Nip\Records\Locator\Resolver\Pipeline\Stages\TableNameClassInstance;

/**
 * Class MethodsPipeline
 * @package Nip\Records\Locator\Resolver\Pipeline
 */
class PipelineBuilder extends AbstractBuilder
{
    public function __construct()
    {
        $this->add(new RegistryInstance());
        $this->add(new ClassInstance());
        $this->add(new NamespaceSearch());
        $this->add(new TableNameClassInstance());
        $this->add(new DefaultClassInstance());
    }

    /**
     * Build a new Pipeline object
     *
     * @param  ProcessorInterface|null $processor
     *
     * @return PipelineInterface
     */
    public function build(ProcessorInterface $processor = null): PipelineInterface
    {
        if ($processor == null) {
            $processor = new InterruptibleProcessor(
                function (Command $command) {
                    return !$command->hasInstance();
                }
            );
        }
        return parent::build($processor);
    }
}
