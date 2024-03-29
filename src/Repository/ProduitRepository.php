<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function findLatestProductsByEntrepriseId(int $entrepriseId, int $limit = 15): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.entrepriseId = :entrepriseId')
            ->setParameter('entrepriseId', $entrepriseId)
            ->orderBy('p.CreatedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findLatestProducts(int $limit = 15): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.CreatedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


     public function findBestSellers(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.stock', 'ASC') // Sort by stock level in ascending order
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findBestSellersByEntreprise($companyId)
    {
        return $this->createQueryBuilder('p')
            ->select('p.nom, p.prix, COUNT(df.id) as total_sales')
            ->leftJoin('p.detailFactures', 'df')
            ->leftJoin('df.facture', 'f')
            ->leftJoin('f.entrepriseId', 'e')
            ->andWhere('e.id = :companyId')
            ->setParameter('companyId', $companyId)
            ->groupBy('p.id')
            ->orderBy('total_sales', 'DESC')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
