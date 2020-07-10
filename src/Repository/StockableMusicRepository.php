<?php

namespace App\Repository;

use App\Entity\StockableMusic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockableMusic|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockableMusic|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockableMusic[]    findAll()
 * @method StockableMusic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockableMusicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockableMusic::class);
    }

    // /**
    //  * @return StockableMusic[] Returns an array of StockableMusic objects
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
    public function findOneBySomeField($value): ?StockableMusic
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
