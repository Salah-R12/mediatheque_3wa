<?php

namespace App\Repository;

use App\Entity\StockableMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockableMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockableMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockableMedia[]    findAll()
 * @method StockableMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockableMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockableMedia::class);
    }

    // /**
    //  * @return StockableMedia[] Returns an array of StockableMedia objects
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
    public function findOneBySomeField($value): ?StockableMedia
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
