<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class ProduitFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder(): int
    {
        return 5;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $nomsProduits = [
            'Carte Graphique', 'Écran', 'PC de bureau', 'PC portable', 'Clavier', 
            'Souris', 'Disque SSD', 'Processeur', 'Carte mère', 'Mémoire RAM', 
            'Ventilateur PC', 'Boîtier PC', 'Alimentation PC', 'Casque audio', 
            'Enceinte PC', 'Webcam'
        ];

        for ($i = 0; $i < 50; $i++) {
            $produit = new Produit();
            $produit->setNom($faker->randomElement($nomsProduits))
                    ->setDescription($faker->text)
                    ->setPrix($faker->randomFloat(2, 10, 200))
                    ->setStock($faker->numberBetween(0, 100))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisYear()))
                    ->setEntrepriseId($this->getReference('entreprise-'.rand(0, 9)));
                    // ->setCategorieId($this->getReference('categorie-'.rand(0, 9)));

            $manager->persist($produit);
        }

        $manager->flush();
    }
}
