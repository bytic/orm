<?php

namespace Nip\Records\Locator\Resolver;

use League\Pipeline\InterruptibleProcessor;
use Nip\Records\Locator\Resolver\Commands\Command;
use Nip\Records\Locator\Resolver\Pipeline\PipelineBuilder;
use Nip\Records\Locator\Resolver\Pipeline\Stages\StageInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Trait HasResolverPipelineTrait
 * @package Nip\Records\Locator\Resolver
 */
trait HasResolverPipelineTrait
{
    /**
     * @var null|PipelineBuilder
     */
    protected $callPipelineBuilder = null;

    /**
     * @param Command $command
     * @return Command
     * @throws ForwardException
     */
    protected function processCommand(Command $command)
    {
        $pipeline = $this->buildCallPipeline();
        return $pipeline->process($command);
    }

    /**
     * @return \League\Pipeline\PipelineInterface|\League\Pipeline\Pipeline
     */
    protected function buildCallPipeline()
    {
        return $this->getCallPipelineBuilder()->build();
    }

    /**
     * @return PipelineBuilder
     */
    public function getCallPipelineBuilder()
    {
        if ($this->callPipelineBuilder === null) {
            $this->initCallPipeline();
        }
        return $this->callPipelineBuilder;
    }

    /**
     * @param StageInterface $stage
     */
    public function addCallPipeline(StageInterface $stage)
    {
        $this->getCallPipelineBuilder()->add($stage);
    }

    /**
     * @param PipelineBuilder $callPipelineBuilder
     */
    public function setCallPipelineBuilder(PipelineBuilder $callPipelineBuilder): void
    {
        $this->callPipelineBuilder = $callPipelineBuilder;
    }


    public function initCallPipeline()
    {
        $this->callPipelineBuilder = (new PipelineBuilder());
    }
}
