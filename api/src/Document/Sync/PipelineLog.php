<?php

namespace App\Document\Sync;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(db="beanworks", collection="pipeline_logs")
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
     * @MongoDB\Field(type="string")
     */
    protected $item;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", name="total_records")
     */
    protected $detail;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", name="timestamp")
     */
    protected $timestamp;

    public function __construct(
        Pipeline $pipeline,
        string $item,
        string $detail
    ) {
        $this->pipeline = $pipeline;
        $this->item = $item;
        $this->detail = $detail;
        $this->timestamp = new \DateTime();;
    }

    /**
     * @return string
     */
    public function getPipeline(): Pipeline
    {

        return $this->pipeline;
    }

    /**
     * @return string
     */
    public function getItem(): string
    {
        return $this->item;
    }


    /**
     * @return \DateTime
     */
    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getDetail(): string
    {
        return $this->detail;
    }
}