<?php

namespace App\Repository;

use App\Entity\Batch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Batch>
 */
class BatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Batch::class);
    }

    //renvoie 3 premiers produits d'une collection
    //SELECT * FROM product p inner JOIN batch b ON p.batch_id = b.id WHERE p.batch_id = :nb ORDER BY title LIMIT 3
    public function find3Product($id){
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        $qb->select('p')
            ->from('App\Entity\Product', 'p')
            ->innerJoin('p.batch', 'b')
            ->andWhere('p.batch = :id')
            ->orderBy('b.title', 'ASC')
            ->setParameter('id', $id)
            ->setMaxResults(3);

        //renvoie le resultat
        $query = $sub->getQuery();
        return $query->getResult();
    }


    //    /**
    //     * @return Batch[] Returns an array of Batch objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Batch
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
