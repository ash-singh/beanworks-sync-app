<?php

namespace App\Sync;

use App\Document\Sync\Pipeline as PipelineDocument;
use App\Document\User;
use App\Xero\Account;
use App\Xero\Vendor;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoDB\BSON\ObjectId;

class Pipeline
{
    public const STATUS_START = 'Start';
    public const STATUS_IN_PROGRESS = 'In-progress';
    public const STATUS_END = 'End';

    public const OPERATION_XERO_SYNC = 'SyncFromXero';

    /** @var DocumentManager */
    private $pipelineManager;

    /** @var PipelineLog */
    private $pipelineLog;

    /** @var Account */
    private $account;

    /** @var Vendor */
    private $vendor;

    /**
     * Pipeline constructor.
     */
    public function __construct(
        DocumentManager $pipelineManager,
        PipelineLog $pipelineLog,
        Account $account,
        Vendor $vendor
    ) {
        $this->pipelineManager = $pipelineManager;
        $this->pipelineLog = $pipelineLog;
        $this->account = $account;
        $this->vendor = $vendor;
    }

    public function getPipelines(string $userMongoID)
    {
        return $this->pipelineManager->createQueryBuilder(PipelineDocument::class)
            ->field('user')->equals(new ObjectId($userMongoID))
            ->getQuery()
            ->execute()->toArray();
    }

    public function getPipelineList(User $user): array
    {
        $pipelineList = [];
        foreach ($this->getPipelines($user->getUserId()) as $pipeline) {
            $pipelineArray = $pipeline->toArray();
            $pipelineArray['logs'] = $this->pipelineLog->getLogs($pipeline->getPipelineId());
            $pipelineList[] = $pipelineArray;
        }

        return $pipelineList;
    }

    public function createPipeline(User $user, string $operation): PipelineDocument
    {
        $pipeline = new PipelineDocument(
            $user,
            $operation,
            self::STATUS_START
        );

        $this->pipelineManager->persist($user);
        $this->pipelineManager->persist($pipeline);

        $this->pipelineManager->flush();

        return $pipeline;
    }

    public function processPipeline(PipelineDocument $pipeline): void
    {
        $this->pipelineLog->markPipelineInProgress($pipeline);

        $this->vendor->syncRecords($pipeline);
        $this->account->syncRecords($pipeline);

        $this->pipelineLog->markPipelineEnd($pipeline);
    }
}
