<?php

namespace App\DataFixtures;

use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EntrepriseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $entreprise = new Entreprise();
            $entreprise->setNom($faker->company)
                       ->setAdresse($faker->address)
                       ->setDescription($faker->realText(200))
                       ->setCreatedAt(new \DateTimeImmutable())
                       ->setEmail($faker->companyEmail)
                       ->setTelephone($faker->numerify('0#########'))
                       ->setSiren($faker->numerify('1########'))
                       ->setCodePostal($faker->postcode)
                       ->setVille($faker->city)
                       ->setSiteWeb($faker->url)
                       ->setLogo(null);

            $manager->persist($entreprise);
        }

        $manager->flush();
    }

}
