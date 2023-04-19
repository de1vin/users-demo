<?php

namespace App\Service;


use App\Doctrine\Tools\Paginator\PaginatedResult;
use App\Doctrine\Tools\Paginator\QueryPaginator;
use App\Dto\User\CreateUserDto;
use App\Dto\User\SearchUserDto;
use App\Dto\User\UpdateUserDto;
use App\Entity\User;
use App\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserService
 */
readonly class UserService
{
    /**
     * @param EntityManagerInterface      $entityManager
     * @param ValidatorInterface          $validator
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * @param int $id
     *
     * @return User
     */
    public function findOr404(int $id): User
    {
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->find($id);

        if ($user === null) {
            throw new NotFoundHttpException('User not found');
        }

        return $user;
    }

    /**
     * @param int  $id
     * @param bool $flush
     *
     * @return User
     */
    public function delete(int $id, bool $flush = true): User
    {
        $user = $this->findOr404($id);

        $this->entityManager->remove($user);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $user;
    }

    /**
     * @param CreateUserDto $userDto
     * @param bool          $flush
     *
     * @return User
     */
    public function create(CreateUserDto $userDto, bool $flush = true): User
    {
        $errors = $this->validator->validate($userDto);

        if (count($errors)) {
            throw new ValidationException($errors);
        }

        $user = new User();
        $user = $this->fillUser($userDto, $user);

        $this->entityManager->persist($user);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $user;
    }

    /**
     * @param int           $id
     * @param UpdateUserDto $userDto
     * @param bool          $flush
     *
     * @return User
     */
    public function update(int $id, UpdateUserDto $userDto, bool $flush = true): User
    {
        $errors = $this->validator->validate($userDto);

        if (count($errors)) {
            throw new ValidationException($errors);
        }

        $user = $this->findOr404($id);
        $user = $this->fillUser($userDto, $user);

        if ($flush) {
            $this->entityManager->flush();
        }

        return $user;
    }

    /**
     * @param SearchUserDto $searchDto
     * @param int           $page
     * @param int           $perPage
     *
     * @return PaginatedResult
     */
    public function search(SearchUserDto $searchDto, int $page = 1, int $perPage = 10): PaginatedResult
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb
            ->select('u')
            ->from(User::class, 'u');
        $this->applySearchFilters($searchDto, $qb);

        $result = new QueryPaginator($qb, $page, $perPage);

        return $result->getResult();
    }

    /**
     * @param CreateUserDto|UpdateUserDto $userDto
     * @param User                        $user
     *
     * @return User
     */
    private function fillUser(CreateUserDto|UpdateUserDto $userDto, User $user): User
    {
        $user
            ->setEmail($userDto->email)
            ->setRoles($userDto->roles);

        if ($userDto->password) {
            $password = $this->passwordHasher->hashPassword($user, $userDto->password);
            $user->setPassword($password);
        }

        return $user;
    }

    /**
     * @param SearchUserDto $searchDto
     * @param QueryBuilder  $qb
     *
     * @return void
     */
    private function applySearchFilters(SearchUserDto $searchDto, QueryBuilder $qb): void
    {
        if ($searchDto->email) {
            $qb->andWhere($qb->expr()->like('u.email', ':email'));
            $qb->setParameter('email', "%$searchDto->email%");
        }

        if ($searchDto->roles) {
            $qb->andWhere($qb->expr()->in('u.roles', ':roles'));
            $qb->setParameter('roles', $searchDto->roles);
        }
    }
}
