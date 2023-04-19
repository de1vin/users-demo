<?php

namespace App\DataFixtures\User;


use App\DataFixtures\BaseFixture;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixture
 */
class UserFixture extends BaseFixture implements FixtureGroupInterface
{
    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {}

    /**
     * @return string[]
     */
    public static function getGroups(): array {
        return ['user'];
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            User::class,
            rand(10, 20),
            function (User $user, int $index) {
                $passwordHash = $this->passwordHasher->hashPassword($user, 'secret');

                if ($index === 0) {
                    $user
                        ->setRoles([User::ROLE_ADMIN])
                        ->setEmail('vladimir.zinchenko.tgn@gmail.com');
                } else {
                    $user
                        ->setRoles([User::ROLE_USER])
                        ->setEmail($this->faker->email());
                }

                $user->setPassword($passwordHash);
            }
        );
    }
}
