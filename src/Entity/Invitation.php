<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvitationRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * Many Message have one user. This is the owning side.
     * @JMS\Groups(groups={"addInvi"})
     * @ORM\ManyToOne(targetEntity=User::Class, inversedBy="Invitation", cascade={"persist"})
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    private $sender;
    
    /**
     * Many Message have one user. This is the owning side.
     * @JMS\Groups(groups={"addInvi"})
     * @ORM\ManyToOne(targetEntity=User::Class, inversedBy="Invitation", cascade={"persist"})
     * @ORM\JoinColumn(name="received_id", referencedColumnName="id")
     */
    private $received;
    
    /**
     * @ORM\Column(type="datetime")
     * @JMS\Type("DateTime<'Y-m-d'>")
     * @JMS\Groups(groups={"addInvi"})
     */
    private $dateSent; 
    /**
     * @ORM\Column(type="integer", options={"default" : 0})
     * @JMS\Groups(groups={"addInvi", "acceptInvi"})
     */        
    private $status;
    
    public function __construct() 
    {
        $this->dateSent = new \DateTime();
    }

    /**
     * 
     * @return User
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
    /**
     * 
     * @return type
     */
    public function getDateSent()
    {
        return $this->dateSent;
    }
    /**
     * @ORM\PrePersist
     * @param type $dateSent
     * @return $this
     */
    public function setDateSent()
    {
        $this->dateSent = new \DateTime();
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }
    /**
     * 
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}
