<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $entrepriseIds = [1, 2, 3];

        foreach ($entrepriseIds as $entrepriseId) {
            $entreprise = $manager->getRepository(Entreprise::class)->find($entrepriseId);

            if (!$entreprise) {
                continue;
            }

            for ($i = 0; $i < 10; $i++) {
                $client = new Client();
                $client->setNom($faker->lastName)
                       ->setPrenom($faker->firstName)
                       ->setAdresse($faker->address)
                       ->setEmail($faker->email)
                       ->setTelephone($faker->phoneNumber)
                       ->setCreatedAt(new \DateTimeImmutable())
                       ->setEntrepriseId($entreprise)
                       ->setCodePostal($faker->postcode)
                       ->setVille($faker->city);

                $manager->persist($client);
            }
        }

        $manager->flush();
    }
}
