<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class ExistEmailValidator
 */
class ExistEmailValidator extends ConstraintValidator
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {}

    /**
     * @param mixed      $value
     * @param Constraint $constraint
     *
     * @throws
     */
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof ExistEmail) {
            throw new UnexpectedTypeException($constraint, ExistEmail::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $field = $constraint->field;
        $class = $constraint->entityClass;
        $qb = $this->entityManager->createQueryBuilder();
        $exist = (bool) $qb->select("count(e.$field)")
            ->from($class, 'e')
            ->where("e.$field = :value")
            ->setParameter('value', $value)
            ->getQuery()
            ->getSingleScalarResult();

        if ($exist) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $value)
                ->addViolation();
        }
    }
}
