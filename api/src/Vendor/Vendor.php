<?php

namespace App\Vendor;

use App\Document\User;
use App\Document\Vendor as VendorDocument;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoDB\BSON\ObjectId;

class Vendor
{
    /** @var DocumentManager */
    private $vendorManager;

    public function __construct(DocumentManager $vendorManager)
    {
        $this->vendorManager = $vendorManager;
    }

    public function getVendors(string $userMongoID)
    {
        return $this->vendorManager->createQueryBuilder(VendorDocument::class)
            ->field('user')->equals(new ObjectId($userMongoID))
            ->getQuery()
            ->execute()->toArray()
            ;
    }

    public function getVendorList(User $user)
    {
        $vendorList = [];
        foreach ($this->getVendors($user->getUserId()) as $vendor) {
            $vendorList[] = $vendor->toArray();
        }

        return [
            'count' => count($vendorList),
            'data' => $vendorList,
        ];
    }

    public function removeOldRecord(string $contactId): void
    {
        $this->vendorManager->createQueryBuilder(VendorDocument::class)
            ->remove()
            ->field('contact')->equals($contactId)
            ->getQuery()
            ->execute()
            ;
    }
}
