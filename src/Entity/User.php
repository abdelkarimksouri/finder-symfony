<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
     const USER_ROLES = [
        "ROLE_USER",
        "ROLE_ADMIN"
     ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @JMS\Groups(groups={"user_profil", "addInvi", "addMsg"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Groups(groups={"user_profil", "addUser", "addMsg"})
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @JMS\Groups(groups={"user_profil", "addUser", "put_user", "addMsg"})
     * @var array
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @JMS\Groups(groups={"addUser", "user_profil"})
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @JMS\Groups(groups={"user_profil", "addUser", "put_user"})
     * @ORM\OneToOne(targetEntity=ProfilUser::class, cascade={"persist","remove"})
     */
    protected $profil;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    
    
    /**
     * @return mixed
     */
    public function getProfil()
    {
        return $this->profil;
    }

    /**
     * @param mixed $profil
     * @return User
     */
    public function setProfil($profil)
    {
        $this->profil = $profil;
        return $this;
    }
}
