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
        return 4;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Get all existing Devis entities
        $existingDevis = $manager->getRepository(\App\Entity\Devis::class)->findAll();

        for ($i = 0; $i < 30; $i++) {
            $facture = new Facture();
            $facture->setNumero($faker->numerify('FACT####'))
                    ->setDateFacturation($faker->dateTimeThisDecade())
                    ->setDatePaiementDue($faker->dateTimeThisDecade())
                    ->setStatutPaiement($faker->randomElement(['Payé', 'Non-payé', 'En retard']))
                    ->setTotal($faker->randomFloat(2, 100, 10000))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisDecade()))
                    ->setEntrepriseId($this->getReference('entreprise-'.rand(0, 9)));

            // Inside the load method of FactureFixtures class

            // Fetch the Devis entity based on its Numero
            $randomDevis = $faker->randomElement($existingDevis);
            $devisEntity = $manager->getRepository(\App\Entity\Devis::class)->findOneBy(['numero' => $randomDevis->getNumero()]);

            // Set the Devis entity instead of just its Numero
            $facture->setDevi($devisEntity);


            $manager->persist($facture);
        }

        $manager->flush();
    }
}
