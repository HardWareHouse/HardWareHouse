<?php

namespace App\Repository;

use App\Entity\Devis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Devis>
 *
 * @method Devis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devis[]    findAll()
 * @method Devis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Devis::class);
    }

    public function findByYear($year)
{
    $conn = $this->getEntityManager()->getConnection(); 
    $sql = 
        'SELECT 
            d.date_creation,
            d.total,
            d.numero,
            d.status,
            e.nom as entreprise_nom

        FROM devis d
        INNER JOIN entreprise e ON d.entreprise = e.id
        WHERE EXTRACT(YEAR FROM d.date_creation) = :year';

    $resultSet = $conn->executeQuery($sql, ['year' => $year]);

    return $resultSet->fetchAllAssociative();
}

public function findByEntreprise($value): array
   {
       return $this->createQueryBuilder('d')
           ->andWhere('d.entrepriseId = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findByYearAndCompany($year, $companyId)
{
    $conn = $this->getEntityManager()->getConnection(); 
    $sql = 
        'SELECT 
            d.date_creation,
            d.numero,
            d.total,
            d.status,
            e.nom as entreprise_nom
        FROM devis d
        JOIN entreprise e ON d.entreprise = e.id
        WHERE EXTRACT(YEAR FROM d.date_creation) = :year
        AND e.id = :companyId';

    $resultSet = $conn->executeQuery($sql, ['year' => $year, 'companyId' => $companyId]);

    return $resultSet->fetchAllAssociative();
}
//    /**
//     * @return Devis[] Returns an array of Devis objects
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

//    public function findOneBySomeField($value): ?Devis
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
