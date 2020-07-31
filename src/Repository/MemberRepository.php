<?php
namespace App\Repository;
use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

/**
 *
 * @method Member|null find($id, $lockMode = null, $lockVersion = null)
 * @method Member|null findOneBy(array $criteria, array $orderBy = null)
 * @method Member[] findAll()
 * @method Member[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemberRepository extends ServiceEntityRepository implements UserLoaderInterface{

	public function __construct(ManagerRegistry $registry){
		parent::__construct($registry, Member::class);
	}

	public function findAllExcludePassword(){
		return $this->createQueryBuilder('m')
			->addSelect('m.id')
			->addSelect('m.username')
			->addSelect('m.first_name firstName')
			->addSelect('m.last_name lastName')
			->addSelect('m.email')
			->orderBy('m.username', 'ASC')
			->getQuery()
			->getResult();
	}

	public function loadUserByUsername(string $username){
		return $this->createQueryBuilder('u')
			->where('u.username = :username')
			->setParameter('username', $username)
			->getQuery()
			->getOneOrNullResult();
	}
}
