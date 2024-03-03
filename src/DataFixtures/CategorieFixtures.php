<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CategorieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $nomsCategoriesEntreprise1 = [
            'Ecrans', 'Processeur', 'Carte mère', 'Carte graphique',
            'Alimentation', 'Stockage', 'Mémoire RAM', 'Ventirads', 'PC Portables'
        ];

        $nomsCategoriesEntreprise2 = [
            'Ecrans1', 'Processeur1', 'Carte mère1', 'Carte graphique1',
            'Alimentation1', 'Stockage1', 'Mémoire RAM1', 'Ventirads1', 'PC Portables1'
        ];

        $nomsCategoriesEntreprise3 = [
            'Ecrans2', 'Processeur2', 'Carte mère2', 'Carte graphique2',
            'Alimentation2', 'Stockage2', 'Mémoire RAM2', 'Ventirads2', 'PC Portables2'
        ];

        $this->createCategoriesForEntreprise($manager, $nomsCategoriesEntreprise1, 1);
        $this->createCategoriesForEntreprise($manager, $nomsCategoriesEntreprise2, 2);
        $this->createCategoriesForEntreprise($manager, $nomsCategoriesEntreprise3, 3);

        $manager->flush();
    }

    private function createCategoriesForEntreprise(ObjectManager $manager, array $nomsCategories, int $entrepriseId): void
    {
        $entreprise = $manager->getRepository(Entreprise::class)->find($entrepriseId);
        if (!$entreprise) {
            throw new \Exception("Entreprise avec l'ID $entrepriseId non trouvée.");
        }

        foreach ($nomsCategories as $nomCategorie) {
            $categorie = new Categorie();
            $categorie->setNom($nomCategorie)
                      ->setDescription("Description pour $nomCategorie")
                      ->setCreatedAt(new \DateTimeImmutable())
                      ->setEntrepriseId($entreprise);

            $manager->persist($categorie);
        }
    }

    public function getDependencies()
    {
        return [
            EntrepriseFixtures::class,
            UserFixtures::class,
        ];
    }
}
