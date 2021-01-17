<?php

namespace App\Entity;

use BusinessBundle\Entity\Opportunity;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @JMS\Groups(groups={"addUser", "put_user"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups(groups={"addUser", "put_user", "user_profil"})
     * @ORM\Column(type="string", length=10)
     */
    private $countryCode;

    /**
     * @JMS\Groups(groups={"addUser", "put_user", "user_profil"})
     * @ORM\Column(type="string", length=50)
     */
    private $countryName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    public function setCountryName(string $countryName): self
    {
        $this->countryName = $countryName;

        return $this;
    }
}
