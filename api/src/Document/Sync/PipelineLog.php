<?php

namespace App\Document\Sync;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="beanworks", collection="pipeline_logs")
 *
 * @MongoDB\Index(keys={"pipeline_id": "asc"})
 */
class PipelineLog
{
    /**
     * @var string
     *
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var Pipeline
     *
     * @MongoDB\Index
     * @MongoDB\ReferenceOne(targetDocument="App\Document\Sync\Pipeline", storeAs="id")
     */
    protected $pipeline;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", name="pipeline_id")
     */
    protected $pipelineId;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    protected $item;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", name="detail")
     */
    protected $detail;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="created_on")
     */
    protected $creationDate;

    public function __construct(
        Pipeline $pipeline,
        string $item,
        string $detail
    ) {
        $this->pipeline = $pipeline;
        $this->item = $item;
        $this->detail = $detail;
        $this->creationDate = new \DateTime();
        $this->pipelineId = $pipeline->getPipelineId();
    }

    public function getPipeline(): Pipeline
    {
        return $this->pipeline;
    }

    public function getItem(): string
    {
        return $this->item;
    }

    public function getTimestamp(): \DateTime
    {
        return $this->creationDate;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function getPipelineId(): string
    {
        return $this->pipelineId;
    }

    public function toArray(): array
    {
        return [
            'item' => $this->getItem(),
            'detail' => $this->getDetail(),
            'timestamp' => $this->getTimestamp(),
        ];
    }
}
