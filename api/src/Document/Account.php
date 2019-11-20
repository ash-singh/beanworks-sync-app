<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="beanworks", collection="accounts")
 */
class Account
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
     * @MongoDB\Field(type="string", name="account_id")
     */
    protected $accountId;

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
    protected $code;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", name="bank_account_number")
     */
    protected $bankAccountNumber;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", name="bank_account_type")
     */
    protected $bankAccountType;

    /**
     * @var string|null
     *
     * @MongoDB\Field(type="string")
     */
    protected $type;

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
        string $accountId,
        User $user,
        string $code,
        ?string $name,
        ?string $type,
        ?string $bankAccountNumber,
        ?string $status,
        ?string $bankAccountType,
        \DateTime $updatedDate
        ) {
        $this->accountId = $accountId;
        $this->user = $user;
        $this->code = $code;
        $this->name = $name;
        $this->type = $type;
        $this->bankAccountNumber = $bankAccountNumber;
        $this->status = $status;
        $this->bankAccountType = $bankAccountType;
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

    public function getBankAccountNumber(): ?string
    {
        return $this->bankAccountNumber;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function getBankAccountType(): ?string
    {
        return $this->bankAccountType;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate(): \DateTime
    {
        return $this->updatedDate;
    }
}
