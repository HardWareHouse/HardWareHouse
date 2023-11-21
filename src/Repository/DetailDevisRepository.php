<?php

namespace App\Repository;

use App\Entity\DetailDevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailDevis>
 *
 * @method DetailDevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailDevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailDevis[]    findAll()
 * @method DetailDevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailDevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailDevis::class);
    }

//    /**
//     * @return DetailDevis[] Returns an array of DetailDevis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DetailDevis
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
