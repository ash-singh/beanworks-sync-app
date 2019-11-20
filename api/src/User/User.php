<?php

namespace App\User;

use App\Document\User as UserDocument;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use MongoDB\BSON\ObjectId;

class User
{
    /** @var DocumentRepository */
    private $userRepository;

    public function __construct(DocumentRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserFromToken(string $token): ?UserDocument
    {
        /* @var User|null $user */
        return $this->userRepository->findOneBy([
            '_id' => new ObjectId($this->sanitizeToken($token)),
        ]);
    }

    private function sanitizeToken(string $token): string
    {
        return trim(trim($token, "'"), '"');
    }
}
