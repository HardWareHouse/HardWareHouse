<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class EntrepriseFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        return 1;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $entreprise = new Entreprise();
            $entreprise->setNom($faker->company)
                       ->setAdresse($faker->address)
                       ->setDescription($faker->text)
                       ->setInformationFiscale($faker->randomNumber(9, true))
                       ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisDecade()));

            $manager->persist($entreprise);
            $this->addReference('entreprise-'.$i, $entreprise);
        }

        $manager->flush();
    }
}
