<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class) 
 * @ORM\HasLifecycleCallbacks()
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Many Message have one user. This is the owning side.
     * @JMS\Groups(groups={"updtMessage", "addMsg"})
     * @ORM\ManyToOne(targetEntity=User::Class, inversedBy="Message", cascade={"persist"})
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $sender;
    
    /**
     * Many Message have one user. This is the owning side.
     * @JMS\Groups(groups={"updtMessage", "addMsg"})
     * @ORM\ManyToOne(targetEntity=User::Class, inversedBy="Message", cascade={"persist"})
     * @ORM\JoinColumn(name="received_id", referencedColumnName="id")
     */
    private $received;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @JMS\Groups(groups={"updtMessage", "addMsg"})
     */
    private $body;
    
    /**
     * @ORM\Column(type="datetime")
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     * @JMS\Groups(groups={"updtMessage"})
     */
    private $dateSent;
    
    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     * @JMS\Groups(groups={"updtMessage", "addMsg"})
     */
    private $archived;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     * @JMS\Groups(groups={"updtMessage"})
     */
    private $archivedAt;
 
    public function __construct()
    {
        $this->dateSent = new \DateTime();
    }
    
    /**
     * 
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * 
     * @return type
     */
    public function getSender()
    {
        return $this->sender;
    }
    /**
     * 
     * @param type $sender
     * @return $this
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }
    /**
     * 
     * @return type
     */
    public function getReceived()
    {
        return $this->received;
    }
    /**
     * 
     * @param type $received
     * @return $this
     */
    public function setReceived($received)
    {
        $this->received = $received;
        return $this;
    }
    
    public function getBody()
    {
        return $this->body;
    }
    
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }
    /**
     * 
     * @return type
     */
    public function getDateSent()
    {
        return $this->dateSent;
    }
    /**
     * 
     * @param type $dateSent
     * @return $this
     */
    public function setDateSent()
    {
        $this->dateSent = new \DateTime();
        return $this;
    }
    /**
     * 
     * @return type
     */
    public function getArchived(): ?bool
    {
        return $this->archived;
        
    }
    /**
     * 
     * @param type $archived
     * @return $this
     */
    public function setArchived(bool $archived): self
    {
        $this->archived = $archived;
        return $this;
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
     * @ORM\PostPersist
     * @param type $archivedAt
     * @return $this
     */
    public function setArchivedAt()
    {
        $this->archivedAt = new \DateTime();
    }
}
