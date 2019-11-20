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

    public function getAccountList(User $user): array
    {
        $accountList = [];
        foreach ($this->getAccounts($user->getUserId()) as $account) {
            $accountList[] = $account->toArray();
        }

        return [
            'count' => count($accountList),
            'data' => $accountList
        ];
    }
}
