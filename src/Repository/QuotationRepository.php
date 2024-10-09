<?php

namespace App\Repository;

use App\Entity\Quotation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quotation>
 */
class QuotationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quotation::class);
    }

    /*
    SELECT * FROM quotation q 
    INNER JOIN project p ON q.project_id = p.id
    WHERE q.is_accepted = 0 AND q.date_creation <= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)

    -> trouve les devis qui ont été refusé et qui ont au moins 3 mois, avec leurs projets
    je ne prends pas depuis la table projet directement puisque je compare avec la date de création d'un devis
    */
    public function findOldQuotations(){
        $dateExp = new \DateTime();
        //enleve 3 mois à la date d'ajd
        $dateExp->modify('-3 months');
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('q')
            ->from('App\Entity\Quotation', 'q')
            ->join('q.project', 'p')
            ->where('q.dateCreation <= :dateExp')
            ->andWhere('q.isAccepted = 0')
            ->setParameter('dateExp', $dateExp)
            ;

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /*
    SELECT * FROM quotation q
    INNER JOIN project p
    ON q.project_id = p.id
    WHERE p.firstname LIKE '%...%'

    trouve un devis en fonction du nom, prénom de la personne ou numéro de devis
    */
    public function findFileByName($word){
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('q')
            ->from('App\Entity\Quotation', 'q')
            ->join('q.project', 'p')
            ->where('p.firstname LIKE :word OR p.surname LIKE :word OR q.quotationNumber LIKE :word')
            ->setParameter('word', '%'.$word.'%') ;
        $query = $qb->getQuery();
        return $query->getResult();
        
    }

    //    /**
    //     * @return Quotation[] Returns an array of Quotation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Quotation
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
