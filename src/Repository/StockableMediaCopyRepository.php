<?php

namespace App\Repository;

use App\Entity\StockableMediaCopy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockableMediaCopy|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockableMediaCopy|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockableMediaCopy[]    findAll()
 * @method StockableMediaCopy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockableMediaCopyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockableMediaCopy::class);
    }

    // /**
    //  * @return StockableMediaCopy[] Returns an array of StockableMediaCopy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StockableMediaCopy
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllAvailable(){
        return $this->createQueryBuilder('s')
        	->select("s, SUM(IF(b.stockable_media_copy_id IS NULL OR (b.stockable_media_copy_id IS NOT NULL AND IFNULL(b.return_date, '') <> ''), 1, 0)) AS borrowable")
        	->leftJoin(\App\Entity\Borrow::class, 'b')
        	->groupBy('s.id')
            ->having('borrowable > 0')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
