<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Id\AbstractIdGenerator;

class TokenGenerator extends AbstractIdGenerator
{
    /**
     * This length implies at most 0.000000001% probability of a
     * collision up to 190000 documents so it’s a sensible default.
     *
     * @see https://en.wikipedia.org/wiki/Birthday_problem#Probability_table
     *
     * @var int
     */
    private $bytes = 16;

    public function generate(DocumentManager $dm, $document)
    {
        return self::generateHexadecimal($this->bytes);
    }

    public function setBytes(int $bytes): void
    {
        $this->bytes = $bytes;
    }

    public static function generateHexadecimal(int $bytes): string
    {
        return \unpack('H*', \random_bytes($bytes))[1];
    }
}
