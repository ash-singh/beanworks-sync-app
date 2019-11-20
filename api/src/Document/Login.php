<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\MappedSuperclass
 */
class Login
{
    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date")
     */
    private $date;

    /**
     * @var string|null
     *
     * @MongoDB\Field
     */
    private $ip;

    public function __construct(?string $ip)
    {
        $this->date = new \DateTime();
        $this->ip = $ip;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }
}
