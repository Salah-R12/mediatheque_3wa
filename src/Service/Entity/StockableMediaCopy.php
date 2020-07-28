<?php
namespace App\Service\Entity;
use App\Service\Entity as EntityService;
use App\Entity\StockableMedia;
use App\Entity\StateOfMedia;
use App\Entity\StockableMediaCopy as StockableMediaCopyEntity;

class StockableMediaCopy extends EntityService{

	public function isRemoval(StockableMedia $stockableMedia): bool{
		return (bool)($stockableMedia->getStockableMediaCopies()->count() > $stockableMedia->getStock());
	}

	/**
	 * Dès lors que l'on modifie la valeur de "stock", on doit impacter le nombre d'exemplaires présents dans la médiathèque
	 * Par exemple, si on indique que la quantité "stock" d'un livre est égal à 5,
	 * alors il doit y avoir 5 exemplaires de ce même livre dans la table "stockable_media_copy"
	 *
	 * @param StockableMedia $stockableMedia
	 */
	public function generateCopyFromMedia(StockableMedia $stockableMedia): void{
		// On récupère le vrai nombre d'exemplaires présents dans la médiathèque
		$realStock = $stockableMedia->getStockableMediaCopies()->count();
		// On récupère le nouveau nombre d'exemplaires (stock)
		$propStock = $stockableMedia->getStock();
		if ($propStock < 0)
			$propStock = 0;

		# Cas où l'on ajoute 1 ou plusieurs exemplaires (car la valeur de stock a augmenté)
		if ($propStock > $realStock){

			// D'abord, récupérer l'objet d'instance "MediaState" correspondant à l'ID 3 (état neuf)
			$media_state = $this->doctrine->getRepository(StateOfMedia::class)->findOneBy([
				'id' => 3
			]);
			// Ajout de la différence de média
			for ($i = $realStock + 1; $i <= $propStock; $i++){
				$newStockableMediaCopy = new StockableMediaCopyEntity();
				// La propriété "copy_number" définit donc le numéro de l'exemplaire
				$newStockableMediaCopy->setCopyNumber($i);
				// cette nouvelle instance de "StockableMediaCopy" est liée l'instance "$this" de "StockableMedia"
				$newStockableMediaCopy->setStockableMedia($stockableMedia);
				// Enfin, on applique l'état "neuf" à cette nouvelle copie, considérant que par défaut, un nouvel exemplaire est présupposément neuf
				$newStockableMediaCopy->setMediaState($media_state);
				// Utilisation de l'entity manager de l'objet "doctrine" qui a été injecté (injection de dépendance) dans le constructeur (par le controller)
				$this->doctrine->getManager()->persist($newStockableMediaCopy);
				$stockableMedia->addStockableMediaCopy($newStockableMediaCopy);
			}
		} elseif ($propStock < $realStock){ // Cas où l'on supprime un ou plusieurs exemplaires si "stock" diminue
			foreach ($stockableMedia->getStockableMediaCopies() as $stockableMediaCopy){
				$hasBorrows = $stockableMediaCopy->getBorrows()->count();
				if ($hasBorrows){
					// at first iteration where a copy has borrow, then interrupt removal (break loop)
					break;
				}
				if ($stockableMediaCopy->getCopyNumber() > $propStock){
					$stockableMedia->removeStockableMediaCopy($stockableMediaCopy);
				}
			}
			// set real count of copies to "stock" property
			$stockableMedia->setStock($stockableMedia->getStockableMediaCopies()
				->count());
		}
	}

	/**
	 * Returns the last copy number having at least one borrowing
	 *
	 * @param StockableMedia $stockableMedia
	 * @return int
	 */
	public function copiesHaveBorrows(StockableMedia $stockableMedia): int{
		$copyNumber = 0;
		foreach ($stockableMedia->getStockableMediaCopies() as $stockableMediaCopy){
			if ($stockableMediaCopy->getBorrows()->count() > 0){
				$copyNumber = $stockableMediaCopy->getCopyNumber();
			}
		}
		return $copyNumber;
	}
}