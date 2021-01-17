<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserPublishedRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;



/**
 * @ApiResource
 * @ORM\Entity(repositoryClass=UserPublishedRepository::class)
 */
class UserPublished
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Many publication have one user. This is the owning side.
     * @ORM\ManyToOne(targetEntity=User::Class, inversedBy="UserPublished", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $userPublished;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $publishedText;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $mediaId;

    /**
     * @ORM\Column(type="datetime")
     * @JMS\Type("DateTime<'Y-m-d'>")
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $isArchived;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Type("DateTime<'Y-m-d'>")
     */
    private $archivedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     */
    private $isUpdated;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Type("DateTime<'Y-m-d'>")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="userPublished", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->publishedAt = new \DateTime();
    }


      
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserPublished(): ?User
    {
        return $this->userPublished;
    }
    
    
    public function setUserPublished(User $userPublished)
    {
        $this->userPublished = $userPublished;

        return $this;
    }

    public function getPublishedText(): ?string
    {
        return $this->publishedText;
    }

    public function setPublishedText(?string $publishedText): self
    {
        $this->publishedText = $publishedText;

        return $this;
    }

    public function getMediaId(): ?int
    {
        return $this->mediaId;
    }

    public function setMediaId(?int $mediaId): self
    {
        $this->mediaId = $mediaId;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getIsArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): self
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    public function getArchivedAt(): ?\DateTimeInterface
    {
        return $this->archivedAt;
    }

    public function setArchivedAt(?\DateTimeInterface $archivedAt): self
    {
        $this->archivedAt = $archivedAt;

        return $this;
    }

    public function getIsUpdated(): ?bool
    {
        return $this->isUpdated;
    }

    public function setIsUpdated(?bool $isUpdated): self
    {
        $this->isUpdated = $isUpdated;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUserPublished($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUserPublished() === $this) {
                $comment->setUserPublished(null);
            }
        }

        return $this;
    }

}
