<?php

namespace App\Sync;

use App\Document\Sync\Pipeline as PipelineDocument;
use App\Document\Sync\PipelineLog as PipelineLogDocument;
use Doctrine\ODM\MongoDB\DocumentManager;

class PipelineLog
{
    public const PIPELINE_ITEM_ERP_SYNC = 'SyncFromErp';
    public const PIPELINE_ITEM_TYPE_VENDOR = 'VendorRecords';
    public const PIPELINE_ITEM_TYPE_ACCOUNT = 'AccountRecords';

    /** @var DocumentManager */
    private $documentManager;

    /**
     * PipelineLog constructor.
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function markPipelineInProgress(PipelineDocument $pipeline): void
    {
        $pipeline->setStatus(Pipeline::STATUS_IN_PROGRESS);
        $pipeline->setUpdatedDate(new \DateTime());

        $this->documentManager->persist($pipeline->getUser());
        $this->documentManager->persist($pipeline);
        $this->documentManager->flush();
        $this->documentManager->clear();

        $this->writePipelineLog($pipeline);
    }

    public function writePipelineLog(PipelineDocument $pipeline): void
    {
        $pipelineLog = new PipelineLogDocument(
            $pipeline,
            self::PIPELINE_ITEM_ERP_SYNC,
            'Started to sync items from ERP'
        );

        $this->documentManager->persist($pipeline->getUser());
        $this->documentManager->persist($pipeline);
        $this->documentManager->persist($pipelineLog);

        $this->documentManager->flush();
    }

    public function itemFetchFromErpSuccess(PipelineDocument $pipeline, string $itemType, int $recordCount = 0): void
    {
        $this->write($pipeline, $itemType, $recordCount.' Items fetched from ERP successfully.');
    }

    public function itemFetchFromErpFailed(PipelineDocument $pipeline, string $itemType): void
    {
        $this->write($pipeline, $itemType, 'Failed to fetched from ERP.');
    }

    public function synchedRecord(PipelineDocument $pipeline, string $itemType, int $recordCount = 0): void
    {
        $this->write($pipeline, $itemType, $recordCount.' Records sync finished for the item.');
    }

    public function updateRecordStats(PipelineDocument $pipeline, int $total, int $failed): void
    {
        $pipeline->setFailedRecords($failed);
        $pipeline->setSuccessRecords($total - $failed);
        $pipeline->setTotalRecords($total);
        $pipeline->setUpdatedDate(new \DateTime());

        $this->documentManager->persist($pipeline->getUser());
        $this->documentManager->persist($pipeline);
        $this->documentManager->flush();
    }

    private function write(PipelineDocument $pipeline, string $itemType, string $detail): void
    {
        $pipelineLog = new PipelineLogDocument(
            $pipeline,
            $itemType,
            $detail
        );

        $this->documentManager->persist($pipeline->getUser());
        $this->documentManager->persist($pipeline);
        $this->documentManager->persist($pipelineLog);

        $this->documentManager->flush();
    }

    public function markPipelineEnd(PipelineDocument $pipeline): void
    {
        $pipeline->setStatus(Pipeline::STATUS_END);
        $pipeline->setUpdatedDate(new \DateTime());

        $this->documentManager->persist($pipeline->getUser());
        $this->documentManager->persist($pipeline);
        $this->documentManager->flush();

        $this->write($pipeline, self::PIPELINE_ITEM_ERP_SYNC, 'Pipeline END.');
    }

    public function getPipelineLogs(string $pipelineMongoId): array
    {
        $logs = [];
        foreach ($this->getLogs($pipelineMongoId) as $log) {
            $log['created_on'] = $log['created_on']->toDateTime();
            $logs[] = $log;
        }

        //var_dump($logs); die("fdf");
        return $logs;
    }

    private function getLogs(string $pipelineMongoId): array
    {
        return   $this->documentManager->createQueryBuilder(PipelineLogDocument::class)
            ->field('pipeline_id')->equals($pipelineMongoId)
            ->hydrate(false)
            ->getQuery()
            ->execute()->toArray()
            ;
    }
}
