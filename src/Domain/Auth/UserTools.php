<?php


namespace App\Domain\Auth;


use App\Domain\Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserTools
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    public function create(string $username, string $password): string|bool
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword($password);

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            return (string) $errors;
        } else {
            $user->setPassword(
                $this->passwordEncoder->encodePassword($user, $user->getPlainPassword())
            );
            $user->eraseCredentials();

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return true;
    }
}
