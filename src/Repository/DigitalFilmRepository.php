<?php

namespace App\Repository;

use App\Entity\DigitalFilm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DigitalFilm|null find($id, $lockMode = null, $lockVersion = null)
 * @method DigitalFilm|null findOneBy(array $criteria, array $orderBy = null)
 * @method DigitalFilm[]    findAll()
 * @method DigitalFilm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DigitalFilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DigitalFilm::class);
    }

    // /**
    //  * @return DigitalFilm[] Returns an array of DigitalFilm objects
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
    public function findOneBySomeField($value): ?DigitalFilm
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
