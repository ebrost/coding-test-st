<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 *  * @ApiResource(
 *       collectionOperations={
 *          "get",
 *          "post"={
 *              "validation_groups"={"Default", "create"}
 *          },
 *     },
 *     )
 */
class Question
{
    const STATUS = ['draft', 'published'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"question:read", "question:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"question:read", "question:write"})
     * @Assert\NotBlank(groups={"create"})
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"question:read", "question:write"})
     * @Assert\NotNull()
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Type(type="bool")
     */
    private $promoted;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"question:read", "question:write"})
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Choice(choices=Question::STATUS)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="question", orphanRemoval=true, cascade={"persist"})
     * @Groups({"question:read", "question:write"})
     * @Assert\Valid()
     */
    private $answers;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Groups({"question:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updated_at", type="datetime")
     * @Groups({"question:read"})
     */
    private $updatedAt;

    public function __construct()
    {
        $this->promoted = false;
        $this->answers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPromoted(): bool
    {
        return $this->promoted;
    }

    public function setPromoted(bool $promoted): self
    {
        $this->promoted = $promoted;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new \DateTime();
        $this->setUpdatedAt($dateTimeNow);

        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt($dateTimeNow);
        }
    }
}
