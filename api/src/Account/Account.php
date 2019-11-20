<?php

namespace App\Account;

use App\Document\Account as AccountDocument;
use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoDB\BSON\ObjectId;

class Account
{
    /** @var DocumentManager */
    private $accountManager;

    public function __construct(DocumentManager $accountManager)
    {
        $this->accountManager = $accountManager;
    }

    public function getAccounts(string $userMongoID)
    {
        return $this->accountManager->createQueryBuilder(AccountDocument::class)
            ->field('user')->equals(new ObjectId($userMongoID))
            ->getQuery()
            ->execute()->toArray()
            ;
    }

    public function getAccountList(User $user)
    {
        $accountList = [];
        foreach ($this->getAccounts($user->getUserId()) as $account) {
            $accountList[] = [
                'id' => $account->getId(),
                'name' => $account->getName(),
                'bank_account_number' => $account->getBankAccountNumber(),
                'status' => $account->getStatus(),
                'account_id' => $account->getAccountId(),
                'bank_account_type' => $account->getBankAccountType()
            ];
        }

        return [
            'count' => count($accountList),
            'data' => $accountList
        ];
    }
}
