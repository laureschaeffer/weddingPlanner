<?php

namespace App\Repository;

use App\Entity\Creation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Creation>
 */
class CreationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Creation::class);
    }

    //recherche en fonction d'un mot clé dans les enregistrements dans la bdd
    public function findByWord($word) {
        $em = $this->getEntityManager();

        $sub = $em->createQueryBuilder();

        $qb = $sub;

        $qb->select('a')
            ->from('App\Entity\Creation', 'a')
            ->where('a.description LIKE :word')
            ->setParameter('word', '%'.$word.'%');

        $query = $sub->getQuery();
        return $query->getResult();
    }

    //    /**
    //     * @return Creation[] Returns an array of Creation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Creation
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
