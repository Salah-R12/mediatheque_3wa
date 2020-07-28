<?php
namespace App\Service;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Classe parente (abstraite), dont seules les filles seront instanciées (appartenant à l'espace de nom "App\Service\Entity").
 * Chacune des classes filles a pour but de manipuler ou vérifier certaines conditions des classes d'Entités nativement créées par le framework Symfony (dans le répertoire "src/Entity")
 * Par exemple, la classe "App\Service\Entity\StockableMediaCopy" se charge de créer automatiquement les différents exemplaires d'un livre (ou média) en fonction du nombre défini
 * dans la propriété "stock"
 * 
 * Toutes les classes filles vont hériter du constructeur de cette classe et donc, vont avoir une injection de dépendance de l'objet doctrine,
 * de classe "Doctrine\Persistence\ManagerRegistry"
 * 
 * @author fabriced
 *
 */
abstract class Entity{

	/**
	 * @var Doctrine\Persistence\ManagerRegistry;
	 */
	protected $doctrine;

	public function __construct(ManagerRegistry $doctrine){
		$this->doctrine = $doctrine;
	}
}