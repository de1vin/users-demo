<?php

namespace App\Dto\User;


use App\Entity\User;
use App\Validator\ExistEmail;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreateUserDto
 */
class UpdateUserDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    #[ExistEmail(entityClass: User::class)]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: [User::ROLE_USER, User::ROLE_ADMIN], multiple: true)]
    public array $roles;

    #[Assert\NotBlank]
    #[Assert\Length(min: 5)]
    public string $password;

    #[Assert\NotBlank]
    #[Assert\Expression('value == this.password')]
    public string $passwordRepeat;
}
