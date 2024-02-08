<?php

namespace App\DataFixtures;

use App\Entity\Paiement;
use App\Repository\FactureRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PaiementFixtures extends Fixture implements OrderedFixtureInterface
{
    private FactureRepository $factureRepository;

    public function __construct(FactureRepository $factureRepository)
    {
        $this->factureRepository = $factureRepository;
    }

    public function getOrder(): int
    {
        return 6;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $factures = $this->factureRepository->findAll();

        foreach ($factures as $facture) {
            for ($i = 0; $i < 10; $i++) {
                $paiement = new Paiement();
                $paiement->setMontant($faker->randomFloat(2, 99, 9999))
                    ->setDatePaiement($faker->dateTimeThisDecade())
                    ->setMethodePaiement($faker->randomElement(['Carte bancaire', 'Chèque', 'Espèces', 'Virement bancaire']))
                    ->setFactureId($facture);

                $manager->persist($paiement);
            }
        }

        $manager->flush();
    }
}
