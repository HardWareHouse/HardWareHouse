<?php

namespace App\Repository;

use App\Entity\Facture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Facture>
 *
 * @method Facture|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facture|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facture[]    findAll()
 * @method Facture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Facture::class);
    }
public function findFacturesByYear($year)
{
    $conn = $this->getEntityManager()->getConnection(); 
    $sql = 
        'SELECT 
            f.date_facturation,
            f.date_paiement_due,
            f.total,
            f.numero,
            f.statut_paiement,
            e.nom as entreprise_nom
        FROM facture f
        INNER JOIN entreprise e ON f.entreprise = e.id
        WHERE EXTRACT(YEAR FROM f.date_facturation) = :year
        ';

    $resultSet = $conn->executeQuery($sql, ['year' => $year]);

    return $resultSet->fetchAllAssociative();
}

  public function findByEntreprise($value): array
   {
       return $this->createQueryBuilder('f')
           ->andWhere('f.entrepriseId = :val')
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
            f.date_facturation,
            f.numero,
            f.total,
            f.statut_paiement,
            e.nom as entreprise_nom,
            f.date_paiement_due
        FROM facture f
        JOIN entreprise e ON f.entreprise = e.id
        WHERE EXTRACT(YEAR FROM f.date_facturation) = :year
        AND e.id = :companyId';

    $resultSet = $conn->executeQuery($sql, ['year' => $year, 'companyId' => $companyId]);

    return $resultSet->fetchAllAssociative();
}

   
//    /**
//     * @return Facture[] Returns an array of Facture objects
//     */


//    public function findOneBySomeField($value): ?Facture
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
