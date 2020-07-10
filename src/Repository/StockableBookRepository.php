<?php

namespace App\Repository;

use App\Entity\StockableBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockableBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockableBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockableBook[]    findAll()
 * @method StockableBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockableBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockableBook::class);
    }

    // /**
    //  * @return StockableBook[] Returns an array of StockableBook objects
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
    public function findOneBySomeField($value): ?StockableBook
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
