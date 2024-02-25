<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ClientFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        return 2;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $client = new Client();
            $client->setNom($faker->name)
                   ->setAdresse($faker->address)
                   ->setEmail($faker->email)
                   ->setTelephone($faker->phoneNumber)
                   ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisDecade()))
                   ->setEntrepriseId($this->getReference('entreprise-'.rand(0, 9)));

            $manager->persist($client);
        }

        $manager->flush();
    }
}
