<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="history")
 */
class Revision
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $entityClass;

    /**
     * @ORM\Column(type="integer")
     */
    private $entityId;

    /**
     * @ORM\Column(type="json")
     */
    private $updatedContent = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $entityUpdateDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): self
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId($entityId): self
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getUpdatedContent(): ?array
    {
        return $this->updatedContent;
    }

    public function setUpdatedContent(array $updatedContent): self
    {
        $this->updatedContent = $updatedContent;

        return $this;
    }

    public function getEntityUpdateDate(): ?\DateTime
    {
        return $this->entityUpdateDate;
    }

    public function setEntityUpdateDate(\DateTime $entityUpdateDate): self
    {
        $this->entityUpdateDate = $entityUpdateDate;

        return $this;
    }
}
