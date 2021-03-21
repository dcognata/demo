<?php

namespace App\Domain\Auth\Entity;

use App\Domain\Auth\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity({"username"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidV4Generator::class)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=15, unique=true)
     */
    #[Assert\NotBlank]
    #[Assert\Length(
    min: 3,
    max: 15,
    minMessage: 'Your username must be at least {{ limit }} characters long',
    maxMessage: 'Your username cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex('/^[a-zA-Z0-9]+$/')]
    private string $username;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    #[Assert\Length(
        min: 8,
        max: 100,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotCompromisedPassword]
    private ?string $plainPassword;

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    public function __toString(): string
    {
        return (string) $this->username;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }


    public function addRole(string $role): self
    {
        array_push($this->roles, $role);

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
    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
         $this->plainPassword = null;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
}
