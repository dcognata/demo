<?php


namespace App\Domain\Auth\Entity\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class DataToUser
{
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 15,
        minMessage: 'Your username must be at least {{ limit }} characters long',
        maxMessage: 'Your username cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex('/^[a-zA-Z0-9]+$/')]
    private string $username;


    private array $roles = [];

    #[Assert\Length(
        min: 8,
        max: 100,
        minMessage: 'Your password must be at least {{ limit }} characters long',
        maxMessage: 'Your password cannot be longer than {{ limit }} characters',
    )]
    #[Assert\NotCompromisedPassword]
    private string $plainPassword;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function addRole(string $role): self
    {
        array_push($this->roles, $role);

        return $this;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
