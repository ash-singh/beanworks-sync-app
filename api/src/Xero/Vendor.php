<?php

namespace App\Xero;

use App\Document\Sync\Pipeline;
use App\Document\Vendor as VendorDocument;
use App\Sync\PipelineLog;
use App\Vendor\Vendor as VendorService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Contact;

class Vendor
{
    /** @var DocumentManager */
    private $vendorManager;

    /** @var PipelineLog */
    private $pipelineLog;

    /** @var LoggerInterface */
    private $logger;

    /** @var PrivateApplication */
    private $privateApplication;

    /** @var VendorService */
    private $vendorService;

    public function __construct(
        DocumentManager $vendorManager,
        PipelineLog $pipelineLog,
        PrivateApplication $privateApplication,
        VendorService $vendorService,
        LoggerInterface $logger)
    {
        $this->vendorManager = $vendorManager;
        $this->privateApplication = $privateApplication;
        $this->pipelineLog = $pipelineLog;
        $this->logger = $logger;
        $this->vendorService = $vendorService;
    }

    public function syncRecords(Pipeline $pipeline): void
    {
        $failedCount = 0;

        try {
            $contacts = $this->privateApplication->load(Contact::class)->execute();

            $this->pipelineLog->itemFetchFromErpSuccess(
                $pipeline,
                PipelineLog::PIPELINE_ITEM_TYPE_VENDOR,
                $contacts->count()
            );
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch contacts from XERO', [$exception->getMessage()]);
            $this->pipelineLog->itemFetchFromErpFailed($pipeline, PipelineLog::PIPELINE_ITEM_TYPE_VENDOR);

            return;
        }

        $total = 0;
        foreach ($contacts as $contact) {
            if ($contact->getIsSupplier()) {
                ++$total;
                $this->vendorService->removeOldRecord($contact->getContactId());
                $vendorDocument = new VendorDocument(
                    $contact->getContactId(),
                    $pipeline->getUser(),
                    $contact->getName(),
                    $contact->getEmailAddress(),
                    $contact->getContactStatus(),
                    $contact->getUpdatedDateUTC()
                );
                $this->vendorManager->persist($vendorDocument);
                $this->vendorManager->persist($pipeline->getUser());
                try {
                    $this->vendorManager->flush();
                } catch (\Exception $exception) {
                    $this->logger->error('Failed to save vendor with email: '.$contact->getEmailAddress());
                    ++$failedCount;
                }
            }
        }

        $this->pipelineLog->synchedRecord($pipeline, PipelineLog::PIPELINE_ITEM_TYPE_VENDOR, $total);

        $this->pipelineLog->updateRecordStats($pipeline, $total, $failedCount);
    }
}
