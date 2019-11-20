<?php

namespace App\Xero;

use App\Account\Account as AccountService;
use App\Document\Account as AccountDocument;
use App\Document\Sync\Pipeline;
use App\Sync\PipelineLog;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Psr\Log\LoggerInterface;
use XeroPHP\Application\PrivateApplication;
use XeroPHP\Models\Accounting\Account as XeroAccount;

class Account
{
    /** @var DocumentManager */
    private $accountManager;

    /** @var PipelineLog */
    private $pipelineLog;

    /** @var LoggerInterface */
    private $logger;

    /** @var PrivateApplication */
    private $privateApplication;

    /** @var AccountService */
    private $accountService;

    public function __construct(
        DocumentManager $accountManager,
        PipelineLog $pipelineLog,
        PrivateApplication $privateApplication,
        AccountService $accountService,
        LoggerInterface $logger
    ) {
        $this->accountManager = $accountManager;
        $this->pipelineLog = $pipelineLog;
        $this->privateApplication = $privateApplication;
        $this->accountService = $accountService;
        $this->logger = $logger;
    }

    public function syncRecords(Pipeline $pipeline): void
    {
        $failedCount = 0;

        try {
            $accounts = $this->privateApplication->load(XeroAccount::class)->execute();
            $this->pipelineLog->itemFetchFromErpSuccess($pipeline, PipelineLog::PIPELINE_ITEM_TYPE_ACCOUNT);
        } catch (\Exception $exception) {
            $this->logger->error('Failed to fetch accounts from XERO', $exception->getMessage());
            $this->pipelineLog->itemFetchFromErpFailed($pipeline, PipelineLog::PIPELINE_ITEM_TYPE_ACCOUNT);

            return;
        }

        $this->logger->info(\sprintf('Fetched %s Accounts from XERO', count($accounts)));

        foreach ($accounts as $account) {
            $this->accountService->removeOldRecord($account->getAccountID());

            $accountDocument = new AccountDocument(
                $account->getAccountID(),
                $pipeline->getUser(),
                $account->getCode(),
                $account->getName(),
                $account->getType(),
                $account->getBankAccountNumber(),
                $account->getStatus(),
                $account->getBankAccountType(),
                $account->getUpdatedDateUTC()
            );
            $this->accountManager->persist($accountDocument);
            $this->accountManager->persist($pipeline->getUser());
            try {
                $this->accountManager->flush();
            } catch (MongoDBException $exception) {
                $this->logger->error('Failed to save account for pipeline '.$pipeline->getPipelineId());
            }
        }

        $total = count($accounts);

        $this->pipelineLog->synchedRecord($pipeline, PipelineLog::PIPELINE_ITEM_TYPE_ACCOUNT);

        $this->pipelineLog->updateRecordStats($pipeline, $total, $failedCount);
    }
}
