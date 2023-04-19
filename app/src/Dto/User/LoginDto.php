<?php

namespace App\Dto\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LoginDto
 */
class LoginDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $password;
}
