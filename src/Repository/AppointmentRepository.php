<?php

namespace App\Repository;

use App\Entity\Appointment;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    //verifie si la date de rendez-vous est déjà prise ; retourne true si le créneau est pris
    //SELECT * FROM appointment WHERE date_start < :end AND date_end > :start 
    public function isDateTaken($dateStart, $dateEnd): bool
    {
        $qb = $this->createQueryBuilder('a')
            ->where('a.dateStart < :end AND a.dateEnd > :start')
            ->setParameter('start', $dateStart)
            ->setParameter('end', $dateEnd);

        $result = $qb->getQuery()->getResult();

        return count($result) > 0;
    }


    //    /**
    //     * @return Appointment[] Returns an array of Appointment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Appointment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
