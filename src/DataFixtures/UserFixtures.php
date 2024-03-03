<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $username = ['admin', 'comptable', 'user'];
        $mails = ['admin@admin.fr', 'comptable@comptable.fr', 'user@user.fr'];
        $roles = ['ROLE_ADMIN', 'ROLE_COMPTABLE', 'ROLE_USER'];

        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setRoles([$roles[$i]])
                 ->setUsername($username[$i])
                 ->setMail($mails[$i])
                 ->setPassword($password)
                 ->setIsVerified(true)
                 ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisDecade()));

            $entreprise = $manager->getRepository(Entreprise::class)->find($i + 1);
            if (!$entreprise) {
                throw new \Exception("Entreprise avec l'ID ".($i + 1)." non trouvée. Les entreprises ont-elles été chargées correctement ? Verifiez les id dans la base.");
            }
            $user->setEntreprise($entreprise);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EntrepriseFixtures::class,
        ];
    }
}
