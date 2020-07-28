<?php

namespace App\Repository;

use App\Entity\Borrow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Borrow|null find($id, $lockMode = null, $lockVersion = null)
 * @method Borrow|null findOneBy(array $criteria, array $orderBy = null)
 * @method Borrow[]    findAll()
 * @method Borrow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BorrowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Borrow::class);
    }

    // /**
    //  * @return Stream[] Returns an array of Stream objects
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
    public function findOneBySomeField($value): ?Stream
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findLast(int $limit)
    {
        return $this->createQueryBuilder('b')
            ->innerJoin('b.member', 'bm')
            ->innerJoin('b.stockable_media_copy', 'smc')
            ->innerJoin('smc.stockable_media', 'sm')
            ->innerJoin('sm.media', 'm')
            ->innerJoin('m.media_type', 'mt')
            ->orderBy('b.id', 'DESC')
            ->setMaxResults($limit)
            ->select('b,bm,smc,sm,m,mt')
            ->getQuery()
            ->getResult()
            ;
    }

    public function lateBorrows(){
        $query = $this->createQueryBuilder('b')
            ->select('b,bm,smc,sm,m,mt')
            ->innerJoin('b.member','bm')
            ->innerJoin('b.stockable_media_copy', 'smc')
            ->innerJoin('smc.stockable_media', 'sm')
            ->innerJoin('sm.media', 'm')
            ->innerJoin('m.media_type', 'mt')
            ->where('b.expiry_date < CURRENT_DATE()') // Note de FD: TODO Attention, il manque une condition -> il faut tester que "return_date" est null ou pas, car s'il y a une date de
            // restitution, c'est normal que expiry_date soit inférieur à current_date (pour les anciens emprunts)
            ->getQuery()
            ->getResult();

        return $query;

    }

}
