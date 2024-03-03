<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class ProduitFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        $produitsDetails = [
            'Ecrans' => [
                'Samsung Odyssey G9' => 1, 
                'Dell Alienware AW3420DW' => 2, 
                'LG UltraGear 27GL850' => 3,
                'BenQ Zowie XL2540' => 1,
                'Acer Predator XB273K' => 2,
                'ASUS TUF Gaming VG289Q' => 3,
                'MSI Optix MAG274QRF-QD' => 1,
                'AOC C24G1' => 2,
                'ViewSonic Elite XG270QG' => 3,
                'Samsung Odyssey G7' => 1,
                'Dell Alienware AW2521HFL' => 2,
                'LG UltraGear 38GN950' => 3,
            ],
            'Processeur' => [
                'AMD Ryzen 7 5800X' => 1, 
                'Intel Core i9-10900K' => 2, 
                'AMD Ryzen 5 3600' => 3,
                'Intel Core i5-10600K' => 1,
                'AMD Ryzen 3 3300X' => 2,
                'Intel Core i7-9700K' => 3,
                'AMD Ryzen 9 5900X' => 1,
                'Intel Core i3-10100' => 2,
                'AMD Ryzen 5 5600X' => 3,
                'Intel Core i9-11900K' => 1,
                'AMD Ryzen 7 3700X' => 2,
                'Intel Core i5-10400F' => 3,
                'AMD Ryzen 3 3100' => 1,
                'Intel Core i7-10700K' => 2,
                'AMD Ryzen 9 5950X' => 3,
                'Intel Core i3-9100' => 1,
                'AMD Ryzen 5 2600' => 2,
                'Intel Core i9-10850K' => 3,
                'AMD Ryzen 7 3800X' => 1,
                'Intel Core i5-9600K' => 2,
                'AMD Ryzen 5 3600X' => 3,
                'Intel Core i7-9700F' => 1,
                'AMD Ryzen 9 3900X' => 2,
                'Intel Core i3-10100F' => 3,
            ],
            'Carte mère' => [
                'ASUS TUF Gaming X570-Plus' => 1, 
                'MSI MAG B460M Mortar WiFi' => 2, 
                'ASRock B550 Phantom Gaming 4' => 3,
                'Gigabyte Z390 Aorus Ultra' => 1,
                'MSI MEG Z490 Godlike' => 2,
                'ASUS Prime Z390-A' => 3,
                'Gigabyte B450 Aorus M' => 1,
                'ASRock B450M Steel Legend' => 2,
                'MSI MPG B550 Gaming Edge WiFi' => 3,
                'Gigabyte Z490 Aorus Master' => 1,
                'ASUS ROG Strix B550-F Gaming' => 2,
                'MSI MEG Z490I Unify' => 3,
                'Gigabyte B550 Aorus Pro' => 1,
                'ASRock B450M Pro4' => 2,
                'MSI MPG Z490 Gaming Edge WiFi' => 3,
            ],
            'Carte graphique' => [
                'NVIDIA RTX 3090' => 1, 
                'AMD Radeon RX 6900 XT' => 2, 
                'NVIDIA RTX 3070' => 3,
                'AMD Radeon RX 6700 XT' => 1,
                'NVIDIA GTX 1660 Super' => 2,
                'AMD Radeon RX 5600 XT' => 3,
                'NVIDIA RTX 3060 Ti' => 1,
                'AMD Radeon RX 6800' => 2,
                'NVIDIA RTX 3080' => 3,
                'AMD Radeon RX 6600 XT' => 1,
                'NVIDIA RTX 3060' => 2,
                'AMD Radeon RX 5700 XT' => 3,
                'NVIDIA GTX 1650 Super' => 1,
                'AMD Radeon RX 5500 XT' => 2,
                'NVIDIA RTX 2080 Ti' => 3,
                'AMD Radeon RX 5600' => 1,
                'NVIDIA RTX 2080 Super' => 2,
                'AMD Radeon RX 5700' => 3,
                'NVIDIA RTX 2070 Super' => 1,
                'AMD Radeon RX 5500' => 2,
                'NVIDIA RTX 2060 Super' => 3,
                'AMD Radeon RX 590' => 1,
                'NVIDIA GTX 1660' => 2,
                'AMD Radeon RX 580' => 3,
            ],
            'Alimentation' => [
                'EVGA SuperNOVA 750 G5' => 1, 
                'Thermaltake Toughpower GF1' => 2, 
                'NZXT C750' => 3,
                'Corsair AX1600i' => 1,
                'Seasonic Prime 850 Titanium' => 2,
                'Cooler Master MasterWatt 750' => 3,
                'be quiet! Straight Power 11 750W' => 1,
                'SilverStone SX700-G' => 2,
                'Antec Earthwatts Gold Pro 750W' => 3,
                'FSP Hydro PTM 750W' => 1,
                'Fractal Design Ion+ 860P' => 2,
                'XPG Core Reactor 750W' => 3,
                'Thermaltake Toughpower Grand RGB 750W' => 1,
                'EVGA SuperNOVA 650 G5' => 2,
                'NZXT C650' => 3,
            ],
            'Stockage' => [
                'Crucial MX500 1TB' => 1, 
                'Samsung 860 EVO 2TB' => 2, 
                'Kingston A2000,
                NVMe 1TB' => 3,
                'WD Black SN750 NVMe SSD 1TB' => 1,
                'Seagate FireCuda 520 2TB' => 2,
                'Crucial P5 2TB NVMe SSD' => 3,
                'Samsung 970 EVO Plus 1TB' => 1,
                'Adata XPG SX8200 Pro 2TB' => 2,
                'Sabrent Rocket Q 2TB' => 3,
                'WD Blue SN550 1TB' => 1,
                'Gigabyte Aorus NVMe Gen4 2TB' => 2,
                'Corsair MP600 2TB' => 3,
                'Samsung 970 EVO 1TB' => 1,
                'Intel 665p 2TB' => 2,
                'Crucial P2 2TB NVMe SSD' => 3,
                ],
            'Mémoire RAM' => [
                'Corsair Dominator Platinum RGB 32GB' => 1,
                'G.Skill Ripjaws V 16GB' => 2,
                'Teamgroup T-Force Vulcan Z 32GB' => 3,
                'Patriot Viper Steel 16GB' => 1,
                'HyperX Predator DDR4 RGB 64GB' => 2,
                'Crucial Ballistix RGB 3200 MHz 32GB' => 3,
                'Adata XPG Spectrix D60G 16GB' => 1,
                'Thermaltake TOUGHRAM RGB 16GB' => 2,
                'GeIL EVO X II 32GB' => 3,
                'Patriot Viper 4 Blackout 16GB' => 1,
                'G.Skill Trident Z RGB 16GB' => 2,
                'Corsair Vengeance LPX 16GB' => 3,
                'Kingston HyperX Fury 16GB' => 1,
                'Crucial Ballistix 16GB' => 2,
                'Adata XPG Spectrix D41 16GB' => 3,
                'G.Skill Trident Z Neo 32GB' => 1,
                'Corsair Vengeance RGB Pro 32GB' => 2,
                'Patriot Viper Steel RGB 16GB' => 3,
                ],
            'Ventirads' => [
                'Arctic Freezer 34 eSports DUO' => 1,
                'Thermaltake Floe Riing RGB 360 TT' => 2,
                'NZXT Kraken X73' => 3,
                'Deepcool Assassin III' => 1,
                'Scythe Mugen 5 Rev.B' => 2,
                'EKWB EK-AIO 240 D-RGB' => 3,
                'Noctua NH-D15' => 1,
                'Cooler Master Hyper 212 RGB Black Edition' => 2,
                'be quiet! Dark Rock Pro 4' => 3,
                'Corsair H100i RGB PLATINUM' => 1,
                'Arctic Liquid Freezer II 240' => 2,
                'Thermaltake Water 3.0 360 ARGB' => 3,
                'NZXT Kraken Z63' => 1,
                'Deepcool Castle 240EX' => 2,
                'Scythe Fuma 2' => 3,
                'EKWB EK-AIO 360 D-RGB' => 1,
                'Thermaltake Water 3.0 240 ARGB' => 2,
                'Cooler Master MasterLiquid ML240L RGB' => 3,
                ],
            'PC Portables' => [
                'Lenovo Legion 5 Pro' => 1,
                'Dell G3 15' => 2,
                'HP Omen 15' => 3,
                'Razer Blade 15 Advanced' => 1,
                'Alienware m15 R4' => 2,
                'Acer Nitro 5' => 3,
                'Asus ROG Zephyrus G14' => 1,
                'MSI GS66 Stealth' => 2,
                'Gigabyte Aorus 15G' => 3,
                'Razer Blade Stealth 13' => 1,
                'Lenovo Legion 5' => 2,
                'Dell XPS 15' => 3,
                'HP Pavilion Gaming 15' => 1,
                'Acer Predator Helios 300' => 2,
                'Asus TUF Gaming A15' => 3,
                'MSI GE66 Raider' => 1,
                'Gigabyte Aero 15' => 2,
                'Razer Blade 17 Pro' => 3,
                'Alienware Area-51m' => 1,
                'Asus ROG Strix Scar 15' => 2,
                'MSI Prestige 14' => 3,
                'Gigabyte Aorus 17G' => 1,
                'Razer Blade Pro 17' => 2,
                'Alienware m17 R4' => 3,
                ],
            ];        
        

        foreach ($produitsDetails as $categorieNom => $produits) {
            $categorie = $manager->getRepository(Categorie::class)->findOneBy(['nom' => $categorieNom]);

            foreach ($produits as $nomProduit => $entrepriseId) {
                $entreprise = $manager->getRepository(Entreprise::class)->find($entrepriseId);
                
                if (!$entreprise || !$categorie) {
                    continue;
                }

                $produit = new Produit();
                $produit->setNom($nomProduit)
                        ->setDescription("Description pour $nomProduit")
                        ->setPrix($faker->randomFloat(2, 50, 1500))
                        ->setStock($faker->numberBetween(10, 100))
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setEntrepriseId($entreprise)
                        ->setCategorieId($categorie);

                $manager->persist($produit);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            EntrepriseFixtures::class,
            CategorieFixtures::class,
        ];
    }
}
