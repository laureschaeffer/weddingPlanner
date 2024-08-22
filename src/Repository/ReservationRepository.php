<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    //trouve les reservations dont la date pour récupérer était il y a plus de deux ans
    // SELECT * FROM reservation r WHERE r.date_picking < DATE_SUB(CURDATE(), INTERVAL 2 YEAR);
    public function findOldReservations(){
        $dateExp = new \DateTime();
        //enleve 2 ans à la date d'ajd afin
        $dateExp->modify('-2 years');
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('App\Entity\Reservation', 'r')
            ->where('r.datePicking < :dateExp')
            ->setParameter('dateExp', $dateExp)
            ;

        $query = $qb->getQuery();
        return $query->getResult();
    }

    //    /**
    //     * @return Reservation[] Returns an array of Reservation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservation
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
