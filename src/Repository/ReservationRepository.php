<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
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

    //liste des reservations, préparées ou non préparées en fonction du paramètre
    public function paginateReservations(int $page, bool $bool): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('r')
                ->where('r.isPrepared = :bool')
                ->orderBy('r.datePicking', 'DESC')
                ->setParameter('bool', $bool)
            ,
            $page,
            12
        );

        /*
        return new Paginator($this
            ->createQueryBuilder('a')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->setHint(Paginator::HINT_ENABLE_DISTINCT, false), false
        );
        */
    }

    //recherche en fonction d'un mot clé dans les enregistrements dans la bdd
    public function findByWord($word) {
        $em = $this->getEntityManager();

        $sub = $em->createQueryBuilder();

        $qb = $sub;

        $qb->select('r')
            ->from('App\Entity\Reservation', 'r')
            ->where('r.referenceOrder LIKE :word OR r.firstname LIKE :word OR r.surname LIKE :word OR r.telephone LIKE :word')
            ->setParameter('word', '%'.$word.'%');

        $query = $sub->getQuery();
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
