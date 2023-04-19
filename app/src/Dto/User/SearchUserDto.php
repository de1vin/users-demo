<?php

namespace App\Dto\User;


/**
 * Class SearchUserDto
 */
class SearchUserDto
{
    public string|null $email = '';

    public array|null $roles = [];
}
