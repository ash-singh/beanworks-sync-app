<?php

namespace App\Message\Sync;

use \App\Document\Sync\Pipeline as PipelineDocument;

class Pipeline
{
    /**
     * @var PipelineDocument
     */
    private $pipelineDocument;

    public function __construct(PipelineDocument $pipelineDocument)
    {
        $this->pipelineDocument = $pipelineDocument;
    }

    /**
     * @return PipelineDocument
     */
    public function getPipeline(): PipelineDocument
    {
        return $this->pipelineDocument;
    }
}
