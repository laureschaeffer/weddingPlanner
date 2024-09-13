<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
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

    //SELECT DATE_FORMAT(p.date_receipt, "%c") AS month, COUNT(*) AS count
    // FROM project p
    // GROUP BY p.dateReceipt
    // ORDER BY p.dateReceipt

    //trouve les projets groupés par date de reception en mois
    public function findProjectByMonth(){
        $qb = $this->getEntityManager()
        ->createQueryBuilder()

        ->select("DATE_FORMAT(p.dateReceipt, '%M') AS month, COUNT(p) AS count")
        ->from('App\Entity\Project', 'p')
        ->groupBy('p.dateReceipt')
        ->orderBy('p.dateReceipt', 'ASC');
        

        $query = $qb->getQuery();
        return $query->getResult();
    }
    
    // SELECT DATE_FORMAT(p.date_receipt, "%M") AS month, 
    // SUM(final_price) AS chiffre_affaire
    // FROM project p
    // WHERE p.state_id = 3
    // GROUP BY p.date_receipt
    // ORDER BY p.date_receipt;

    //chiffre d'affaire mensuel sur les projets qui ont été acceptés
    public function findMonthlyRevenue(){
        $qb = $this->getEntityManager()
        ->createQueryBuilder()

        ->select("DATE_FORMAT(p.dateReceipt, '%M') AS month, SUM(p.finalPrice) AS chiffre_affaire")
        ->from('App\Entity\Project', 'p')
        ->where('p.state = 3')
        ->groupBy('p.dateReceipt')
        ->orderBy('p.dateReceipt', 'ASC');
        

        $query = $qb->getQuery();
        return $query->getResult();
    }

    //recherche en fonction d'un mot clé dans les enregistrements dans la bdd
    public function findByWord($word) {
        $em = $this->getEntityManager();

        $sub = $em->createQueryBuilder();

        $qb = $sub;

        $qb->select('p')
            ->from('App\Entity\Project', 'p')
            ->where('p.firstname LIKE :word OR p.surname LIKE :word OR p.email LIKE :word OR p.telephone LIKE :word')
            ->setParameter('word', '%'.$word.'%');

        $query = $sub->getQuery();
        return $query->getResult();
    }

    public function paginate($isContacted, int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('p')
            ->where('p.isContacted = :isContacted')
            ->setParameter('isContacted', $isContacted)
            ->orderBy('p.dateReceipt', 'DESC')
            ,
            $page,
            20
        );
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
