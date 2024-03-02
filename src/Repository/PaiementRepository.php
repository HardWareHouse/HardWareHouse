<?php

namespace App\Repository;

use App\Entity\Paiement;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


/**
 * @extends ServiceEntityRepository<Paiement>
 *
 * @method Paiement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paiement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paiement[]    findAll()
 * @method Paiement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaiementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiement::class);
    }

    public function getTotalPaiementsByEntrepriseId($entrepriseId)
{
    $qb = $this->createQueryBuilder('p');

    $qb->select('SUM(p.montant) as totalPaiements')
       ->where('p.entrepriseId = :entrepriseId')
       ->setParameter('entrepriseId', $entrepriseId);

    $query = $qb->getQuery();

    // Execute the query and return the result
    return $query->getSingleScalarResult();
}

public function findPaymentsByYear($year)
{
    $conn = $this->getEntityManager()->getConnection(); 
    $sql = 
        'SELECT 
            p.date_paiement,
            p.montant,
            p.methode_paiement
        FROM paiement p
        WHERE EXTRACT(YEAR FROM p.date_paiement) = :year';

    $resultSet = $conn->executeQuery($sql, ['year' => $year]);

    return $resultSet->fetchAllAssociative();
}

  public function findByEntreprise($value): array
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.entrepriseId = :val')
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
            p.date_paiement,
            p.montant,
            p.methode_paiement
        FROM paiement p
        JOIN facture f ON p.facture = f.id
        JOIN entreprise e ON f.entreprise = e.id
        WHERE EXTRACT(YEAR FROM p.date_paiement) = :year
        AND e.id = :companyId';

    $resultSet = $conn->executeQuery($sql, ['year' => $year, 'companyId' => $companyId]);

    return $resultSet->fetchAllAssociative();
}

//      * Get payment method counts per month and year
//      *
//      * @return array
//      */
//     public function getPaymentMethodCountsPerMonthAndYear(): array
//     {
//         $conn = $this->getEntityManager()->getConnection(); 
//         $sql = 
//             'SELECT EXTRACT(MONTH FROM p.date_paiement) as month, 
//                     EXTRACT(YEAR FROM p.date_paiement) as year, 
//                     p.methode_paiement as paymentMethod, 
//                     COUNT(p.id) as count 
//              FROM paiement p 
//              GROUP BY paymentMethod, month, year'
//         ;

//         $resultSet = $conn->executeQuery($sql);

//         return $resultSet->fetchAllAssociative();
//     }

//    /**
//     * @return Paiement[] Returns an array of Paiement objects
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

//    public function findOneBySomeField($value): ?Paiement
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
