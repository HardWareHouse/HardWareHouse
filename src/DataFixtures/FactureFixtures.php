<?php

namespace App\DataFixtures;

use App\Entity\Facture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class FactureFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        return 3;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $facture = new Facture();
            $facture->setNumero($faker->numerify('FACT####'))
                    ->setDateFacturation($faker->dateTimeThisDecade())
                    ->setDatePaiementDue($faker->dateTimeThisDecade())
                    ->setStatutPaiement($faker->randomElement(['Payé', 'Non-payé', 'En retard']))
                    ->setTotal($faker->randomFloat(2, 100, 10000))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisDecade()))
                    ->setEntrepriseId($this->getReference('entreprise-'.rand(0, 9)));
                    // ->setClientId($this->getReference('client-'.rand(0, 9)));

            $manager->persist($facture);
        }

        $manager->flush();
    }
}
