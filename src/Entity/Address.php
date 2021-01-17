<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use JMS\Serializer\Annotation as JMS;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups(groups={"chatFinder", "user_profil", "put_user"})
     * @ORM\Column(type="string", length=20)
     */
    private $streetNumber;

    /**
     * @JMS\Groups(groups={"chatFinder", "user_profil", "put_user"})
     * @ORM\Column(type="string", length=255)
     */
    private $streetName;

    /**
     * @JMS\Groups(groups={"chatFinder", "user_profil", "put_user"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $streetComplementary;

    /**
     * @JMS\Groups(groups={"chatFinder", "user_profil", "put_user"})
     * @ORM\Column(type="integer")
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="datetime" , nullable=true)
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @JMS\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $updatedAt;

    /**
     * @JMS\Groups(groups={"chatFinder", "user_profil", "put_user"})
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @JMS\Groups(groups={"chatFinder", "user_profil", "addUser", "put_user"})
     * @ORM\ManyToOne(targetEntity=Country::class, cascade={"persist"})
     */
    protected $country;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(string $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getStreetComplementary(): ?string
    {
        return $this->streetComplementary;
    }

    public function setStreetComplementary(string $streetComplementary): self
    {
        $this->streetComplementary = $streetComplementary;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     * @return $this
     * @throws \Exception
     */
    public function setCreatedAt(\DateTimeInterface $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     * @return $this
     * @throws \Exception
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt)
    {
        $this->createdAt = $updatedAt;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     * @return Address
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }
}