<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     *
     * @ORM\Column(type="string",  length=255)
     */
    private $commentBody;
    
    /**
     * @ORM\Column(type="datetime")
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $commentAt;
    
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
     * @ORM\Column(type="boolean", nullable=true, options={"default" : 0})
     * @JMS\Groups(groups={""})
     */
    private $isArchived;
    
      /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $archivedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $userComment;

    /**
     * @ORM\ManyToOne(targetEntity=UserPublished::class, inversedBy="Comment")
     * @ORM\JoinColumn(name="user_published_id", referencedColumnName="id", nullable=false)
     */
    private $userPublished;

    
    public function __construct()
    {
        $this->commentAt = new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
  
    /**
     * 
     * @return type
     */
    public function getCommentBody()
    {
        return $this->commentBody;
    }
    
    public function setCommentBody(string $commentBody)
    {
        $this->commentBody = $commentBody;
        return $this;
    }


    public function getCommentAt()
    {
        return $this->commentAt;
    }
    

    public function setCommentAt()
    {
        $this->commentAt = new \DateTime();
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
    
    public function getIsArchived()
    {
        return $this->isArchived;
    }
    
    /**
     * 
     * @param int $isArchived
     */
    public function setIsArchived(int $isArchived)
    {
        $this->isArchived = $isArchived;
    }
    
    /**
     * 
     * @return type
     */
    public function getArchivedAt()
    {
        return $this->archivedAt;
    }
    
    /**
     * 
     * @param \DateTimeInterface $archivedAt
     * @return $this
     */
    public function setArchivedAt(\DateTimeInterface $archivedAt)
    {
        $this->archivedAt = $archivedAt;
        return $this;
    }

    public function getUserComment(): ?User
    {
        return $this->userComment;
    }

    public function setUserComment(User $userComment): self
    {
        $this->userComment = $userComment;

        return $this;
    }

    public function getUserPublished(): ?UserPublished
    {
        return $this->userPublished;
    }

    public function setUserPublished(?UserPublished $userPublished): self
    {
        $this->userPublished = $userPublished;

        return $this;
    }

}
