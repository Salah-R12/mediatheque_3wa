<?php

namespace App\Repository;

use App\Entity\DigitalBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DigitalBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method DigitalBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method DigitalBook[]    findAll()
 * @method DigitalBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DigitalBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DigitalBook::class);
    }

    // /**
    //  * @return DigitalBook[] Returns an array of DigitalBook objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DigitalBook
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
