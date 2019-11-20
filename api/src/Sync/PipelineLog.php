<?php

namespace App\Sync;

use App\Document\Sync\Pipeline as PipelineDocument;
use App\Document\Sync\PipelineLog as PipelineLOgDocument;
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
     *
     * @param DocumentManager $pipelineManager
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

        $this->writePipelineLog($pipeline);
    }

    public function writePipelineLog(PipelineDocument $pipeline): void
    {
        $pipelineLog = new PipelineLOgDocument(
            $pipeline,
            self::PIPELINE_ITEM_ERP_SYNC,
            'Started to sync items from ERP'
        );

        $this->documentManager->persist($pipeline->getUser());
        $this->documentManager->persist($pipeline);
        $this->documentManager->persist($pipelineLog);

        $this->documentManager->flush();
    }

    public function itemFetchFromErpSuccess(PipelineDocument $pipeline, string $itemType): void
    {
        $this->write($pipeline, $itemType, 'Items fetched from ERP successfully.');
    }

    public function itemFetchFromErpFailed(PipelineDocument $pipeline, string $itemType): void
    {
        $this->write($pipeline, $itemType, 'Failed to fetched from ERP.');
    }

    public function synchedRecord(PipelineDocument $pipeline, string $itemType): void
    {
        $this->write($pipeline, $itemType, 'Record sync finished for the item.');
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
        $pipelineLog = new PipelineLOgDocument(
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
}
