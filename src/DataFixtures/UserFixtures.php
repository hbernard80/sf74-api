<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; ++$i) {
            $user = new User();

            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail($faker->unique()->safeEmail());

            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                'Azerty123!'
            );

            $user->setPassword($hashedPassword);
            $user->setIsActive($faker->boolean(90));
            $user->setIsVerified($faker->boolean(80));

            if ($faker->boolean(10)) {
                $user->setRoles(['ROLE_ADMIN']);
            }

            $createdAtMutable = $faker->dateTimeBetween('-6 months', 'now');
            $createdAt = \DateTimeImmutable::createFromMutable($createdAtMutable);

            $updatedAtMutable = $faker->dateTimeBetween($createdAtMutable, 'now');
            $updatedAt = \DateTimeImmutable::createFromMutable($updatedAtMutable);

            $user->setCreatedAt($createdAt);
            $user->setUpdatedAt($updatedAt);

            $manager->persist($user);

            // Référence réutilisable dans les autres fixtures
            $this->addReference('user_' . $i, $user);
        }

        $manager->flush();
    }
}