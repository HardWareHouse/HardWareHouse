<?php

namespace App\DataFixtures;

use App\Entity\Devis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class DevisFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        return 4;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 40; $i++) {
            $devis = new Devis();
            $devis->setNumero($faker->numerify('DEVIS####'))
                  ->setDateCreation($faker->dateTimeThisDecade())
                  ->setStatus($faker->randomElement(['En attente', 'Approuvé', 'Refusé']))
                  ->setTotal($faker->randomFloat(2, 100, 10000))
                  ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisDecade()))
                  ->setEntrepriseId($this->getReference('entreprise-'.rand(0, 9)));
                //   ->setClientId($this->getReference('client-'.rand(0, 9)));


            $manager->persist($devis);
        }

        $manager->flush();
    }
}