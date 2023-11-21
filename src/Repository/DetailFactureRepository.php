<?php

namespace App\Repository;

use App\Entity\DetailFacture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailFacture>
 *
 * @method DetailFacture|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailFacture|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailFacture[]    findAll()
 * @method DetailFacture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailFactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailFacture::class);
    }

//    /**
//     * @return DetailFacture[] Returns an array of DetailFacture objects
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

//    public function findOneBySomeField($value): ?DetailFacture
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
