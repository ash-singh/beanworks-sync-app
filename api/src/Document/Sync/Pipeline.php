<?php

namespace App\Document\Sync;


use App\Document\User;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="beanworks", collection="pipelines")
 */
class Pipeline
{
    /**
     * @var string
     *
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var User
     *
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="App\Document\User", storeAs="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    protected $operation;

    /**
     * @var integer
     *
     * @MongoDB\Field(type="int", name="total_records")
     */
    protected $totalRecords;

    /**
     * @var integer
     *
     * @MongoDB\Field(type="int", name="failed_records")
     */
    protected $failedRecords;

    /**
     * @var integer
     *
     * @MongoDB\Field(type="int", name="success_records")
     */
    protected $successRecords;

    /**
     * @var string
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

    /**
     * Pipeline constructor.
     * @param User $user
     * @param string $operation
     * @param string $status
     */
    public function __construct(
        User $user,
        string $operation,
        string $status
    ) {
        $this->user = $user;
        $this->operation = $operation;
        $this->status = $status;
        $this->failedRecords = 0;
        $this->successRecords = 0;
        $this->totalRecords = 0;
        $this->creationDate = new \DateTime();
    }

    /**
     * @return string
     */
    public function getPipelineId(): string
    {

        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate(): \DateTime
    {
        return $this->creationDate;
    }

    /**
     * @return int
     */
    public function getFailedRecords(): ?int
    {
        return $this->failedRecords;
    }

    /**
     * @return int
     */
    public function getSuccessRecords(): ?int
    {
        return $this->successRecords;
    }

    /**
     * @return int
     */
    public function getTotalRecords(): ?int
    {
        return $this->totalRecords;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate(): ?\DateTime
    {
        return $this->updatedDate;
    }

    /**
     * @param int $failedRecords
     */
    public function setFailedRecords(int $failedRecords): void
    {
        $this->failedRecords = $failedRecords;
    }

    /**
     * @param string $operation
     */
    public function setOperation(string $operation): void
    {
        $this->operation = $operation;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @param int $successRecords
     */
    public function setSuccessRecords(int $successRecords): void
    {
        $this->successRecords = $successRecords;
    }

    /**
     * @param int $totalRecords
     */
    public function setTotalRecords(int $totalRecords): void
    {
        $this->totalRecords = $totalRecords;
    }

    /**
     * @param \DateTime $updatedDate
     */
    public function setUpdatedDate(\DateTime $updatedDate): void
    {
        $this->updatedDate = $updatedDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getPipelineId(),
            'operation' => $this->getOperation(),
            'status' => $this->getStatus(),
            'total_count' => $this->getTotalRecords(),
            'failed_count' => $this->getFailedRecords(),
            'success_count' => $this->getSuccessRecords(),
            'created_on' => $this->getCreationDate(),
            'updated_on' => $this->getUpdatedDate()
        ];
    }
}