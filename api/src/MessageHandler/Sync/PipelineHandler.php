<?php

namespace App\MessageHandler\Sync;

use App\Message\Sync\Pipeline as PipelineMessage;
use App\Sync\Pipeline;
use Psr\Log\LoggerInterface;

/**
 * Class PipelineHandler.
 */
class PipelineHandler
{
    /** @var Pipeline */
    private $pipelineManager;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(Pipeline $pipelineManager, LoggerInterface $logger)
    {
        $this->pipelineManager = $pipelineManager;
        $this->logger = $logger;
    }

    public function __invoke(PipelineMessage $pipeline)
    {
        $this->logger->info('Received Pipeline for processing', [
            'user' => $pipeline->getPipeline()->getUser()->getUserId(),
            'operation' => $pipeline->getPipeline()->getOperation(),
            'pipeline_id' => $pipeline->getPipeline()->getPipelineId(),
        ]);

        $this->pipelineManager->processPipeline($pipeline->getPipeline());
    }
}
