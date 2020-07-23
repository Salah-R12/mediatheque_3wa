<?php
namespace App\Repository;
use App\Entity\StateOfMedia;
use App\Entity\StockableMedia;
use App\Entity\StockableMediaCopy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Borrow;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 *
 * @method StockableMediaCopy|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockableMediaCopy|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockableMediaCopy[] findAll()
 * @method StockableMediaCopy[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockableMediaCopyRepository extends ServiceEntityRepository{

	public function __construct(ManagerRegistry $registry){
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

	/**
	 * Returns from database all "stockable_media_copies" that are free for borrowing
	 * If you pass an argument, then this function can just return the QueryBuilder instead of the result (array)
	 * 
	 * @param string $return_type (optional)
	 *        	Can be either \Doctrine\ORM\QueryBuilder::class | \Doctrine\ORM\Query::class | null
	 *        	"null" (or any unknown string) means that the result will be returned as array (by default)
	 * @param int $selectedBorrowId (optional)
	 * 			This will keep in result the borrow line related to the consulted borrowed media
	 * @return mixed \Doctrine\ORM\QueryBuilder|\Doctrine\ORM\Query|array
	 */
	public function findAllAvailable(string $return_type = null, int $selectedBorrowId = null){
		// ATTENTION ! Ici, on fait la jointure NON PAS avec l'entité Borrows en tant que telle, mais avec la collection "borrows" définit comme relation dans la classe "StockableMediaCopy"
		$mainQb = $this->createQueryBuilder('s')
			->leftJoin('s.borrows', 'b')
			->select('s');
		// Le left join est important (il est lié aux conditions "where" et "orWhere" définies à la fin)
		// La requête ci-dessus (pour l'instant, car il va s'ajouter des conditions) équivaut à un MySQL :
		// SELECT s.* FROM stockable_media_copy AS s LEFT JOIN borrow b ON s.id = b.stockable_media_copy_id

		// On crée un nouveau query builder, non pas relatif à ce repository (qui est lié à l'entité "StockableMediaCopy") mais à une nouvelle requête qui sera incluse dans "$mainQb"
		// De ce fait, on utilise la fonction "EntityManager::createQueryBuilder" native donc de la classe "EntityManager" et non pas la fonction "$this->createQueryBuilder" (contrairement à "$mainQb") 
		// qui est un ersatz de la même fonction mais déclarée dans la classe "ServiceEntityRepository", qui prend par contre obligatoirement 1 paramètre
		$subQb = $this->getEntityManager()
			->createQueryBuilder()
			->from(Borrow::class, 'b2')
			->select('s2.id')
			->distinct()
			->innerJoin('b2.stockable_media_copy', 's2')
			->where('b2.return_date IS NULL');

		// Exp permet de créer des conditions (SQL) sur la plupart des mots clés ("IN", "NOT IN", "=", "LIKE" etc.)
		$exp = $mainQb->expr();

		// Ici, la fonction "notIn" permet de passer en second paramètre, la "sous-requête" "$subQb" convertie en chaîne de caractère au format pseudo-SQL de Doctrine
		$mainQb->where($exp->notIn('s.id', $subQb->getDQL()))
			->orWhere('b IS NULL')
			->orderBy('s.id', 'ASC');
		
		if ($selectedBorrowId){
			$mainQb->orWhere('s.id = :id')->setParameter('id', $selectedBorrowId);
		}

		switch ($return_type) {
			case QueryBuilder::class:
				return $mainQb;
			case Query::class:
				return $mainQb->getQuery();
			default:
				return $mainQb->getQuery()->getResult();
		}
	}
}
