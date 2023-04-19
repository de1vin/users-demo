<?php

namespace App\Validator;


use Symfony\Component\Validator\Attribute\HasNamedArguments;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
/**
 * Class ExistEmail
 */
class ExistEmail extends Constraint
{
    #[HasNamedArguments]
    public function __construct(
        public string $entityClass,
        public string $field = 'email',
        array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct(null, $groups, $payload);
    }

    public string $message = 'Email {{ email }} already exists.';
}
