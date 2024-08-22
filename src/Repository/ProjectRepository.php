<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    //trouve les projets dont la date de l'évènement était il y a plus de deux ans
    public function findOldProjects(){
        $dateExp = new \DateTime();
        //enleve 2 ans à la date d'ajd afin d'obtenir à partir de quand un projet est expiré
        $dateExp->modify('-2 years');
        $qb = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Project', 'p')
            ->where('p.dateEvent < :dateExp')
            ->setParameter('dateExp', $dateExp)
            ;

        $query = $qb->getQuery();
        return $query->getResult();
    }

    //SELECT DATE_FORMAT(date_receipt, "%c") AS month, COUNT(*) AS count
    // FROM project
    // GROUP BY month
    // ORDER BY MONTH

    //trouve les projets groupés par date de reception en mois
    public function findProjectByMonth(){
        $qb = $this->getEntityManager()
        ->createQueryBuilder()

        ->select("DATE_FORMAT(p.dateReceipt, '%M') AS month, COUNT(p) AS count")
        ->from('App\Entity\Project', 'p')
        ->groupBy('month')
        ->orderBy('month', 'ASC');
        

        $query = $qb->getQuery();
        return $query->getResult();
    }

    //    /**
    //     * @return Project[] Returns an array of Project objects
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

    //    public function findOneBySomeField($value): ?Project
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
