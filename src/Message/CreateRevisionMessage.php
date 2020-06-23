<?php

namespace App\Message;

/**
 * Class CreateRevisionMessage
 * @package App\Message
 */
class CreateRevisionMessage
{
    /**
     * @var string
     */
    private $entityClassName;
    /**
     * @var iterable
     */
    private $updatedContent;
    /**
     * @var \Datetime
     */
    private $entityUpdateDate;
    /**
     * @var int
     */
    private $entityId;

    public function __construct(string $entityClassName, int $entityId, iterable $updatedContent, \Datetime $entityUpdateDate)
    {
        $this->entityClassName = $entityClassName;
        $this->updatedContent = $updatedContent;
        $this->entityUpdateDate = $entityUpdateDate;
        $this->entityId = $entityId;
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    public function getEntityId(): int
    {
        return $this->entityId;
    }

    public function getUpdatedContent(): iterable
    {
        return $this->updatedContent;
    }

    public function getEntityUpdateDate(): \Datetime
    {
        return $this->entityUpdateDate;
    }
}
