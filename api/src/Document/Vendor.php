<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="beanworks", collection="vendors")
 */
class Vendor
{
    /**
     * @var string
     *
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", name="contact_id")
     */
    protected $contactId;

    /**
     * @var User
     *
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="App\Document\User", storeAs="id")
     */
    private $user;

    /**
     * @var string|null
     *
     * @MongoDB\Field(type="string")
     */
    protected $name;

    /**
     * @var string|null
     *
     * @MongoDB\Field(type="string")
     */
    protected $email;

    /**
     * @var string|null
     *
     * @MongoDB\Field(type="string")
     */
    protected $status;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="created_on")
     */
    protected $creationDate;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="updated_on")
     */
    protected $updatedDate;

    public function __construct(
        string $contactId,
        User $user,
        ?string $name,
        ?string $email,
        ?string $status,
        \DateTime $updatedDate
        ) {
        $this->contactId = $contactId;
        $this->user = $user;
        $this->email = $email;
        $this->name = $name;
        $this->status = $status;
        $this->creationDate = $updatedDate;
        $this->updatedDate = $updatedDate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getContactId(): string
    {
        return $this->contactId;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setContactId(string $contactId): void
    {
        $this->contactId = $contactId;
    }

    /**
     * @param \DateTime $updatedDate
     */
    public function setUpdatedDate(\DateTime $updatedDate): void
    {
        $this->updatedDate = $updatedDate;
    }
}
