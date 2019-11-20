<?php

namespace App\Xero;

use App\Document\Vendor as VendorDocument;
use App\Sync\PipelineLog;
use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Log\LoggerInterface;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Contact;
use \App\Document\Sync\Pipeline;

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

    public function __construct(
        DocumentManager $vendorManager,
        PipelineLog $pipelineLog,
        PrivateApplication $privateApplication,
        LoggerInterface $logger)
    {
        $this->vendorManager = $vendorManager;
        $this->privateApplication = $privateApplication;
        $this->pipelineLog = $pipelineLog;
        $this->logger = $logger;
    }

    public function syncRecords(Pipeline $pipeline): void
    {
        $failedCount = 0;

        try {
            $contacts = $this->privateApplication->load(Contact::class)->execute();

            $this->pipelineLog->itemFetchFromErpSuccess($pipeline, PipelineLog::PIPELINE_ITEM_TYPE_VENDOR);

        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch contacts from XERO', [$exception->getMessage()]);
            $this->pipelineLog->itemFetchFromErpFailed($pipeline, PipelineLog::PIPELINE_ITEM_TYPE_VENDOR);
            return;
        }

        foreach ($contacts as $contact) {
            if ($contact->getIsSupplier()) {
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
                try{
                    $this->vendorManager->flush();

                } catch (\Exception $exception) {
                    $this->logger->error('Failed to save vendor with email: ' . $contact->getEmailAddress());
                    ++$failedCount;
                }

            }
        }

        $total = count($contacts);

        $this->pipelineLog->synchedRecord($pipeline, PipelineLog::PIPELINE_ITEM_TYPE_VENDOR);

        $this->pipelineLog->updateRecordStats($pipeline, $total, $failedCount);


    }

}
