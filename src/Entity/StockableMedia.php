<?php
namespace App\Entity;
use App\Repository\StockableMediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Persistence\ManagerRegistry;

/**
 *
 * @ORM\Entity(repositoryClass=StockableMediaRepository::class)
 */
class StockableMedia{

	/**
	 *
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $stock;

	/**
	 *
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $reception_date;

	/**
	 *
	 * @ORM\OneToOne(targetEntity=Media::class, inversedBy="stockableMedia")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $media;

	/**
	 *
	 * @ORM\OneToMany(targetEntity=StockableMediaCopy::class, mappedBy="stockable_media", orphanRemoval=true)
	 */
	private $stockableMediaCopies;

	public function __construct(){
		$this->stockableMediaCopies = new ArrayCollection();
	}

	public function getId(): ?int{
		return $this->id;
	}

	public function getStock(): ?int{
		return $this->stock;
	}

	public function setStock(?int $stock): self{
		// Dès lors que l'on modifie la valeur de "stock", on doit impacter le nombre d'exemplaires présents dans la médiathèque
		// Par exemple, si on indique que la quantité "stock" d'un livre est égal à 5,
		// alors il doit y avoir 5 exemplaires de ce même livre dans la table "stockable_media_copy"
		if ($doctrine = $this->getMedia()->getDoctrine()){ // Attention, ici on utilise l'instance de "Doctrine\Registry" provenant du controller, mais injecté dans l'objet "Media" (voir le constructeur de la classe "Media")
			// On récupère le vrai nombre d'exemplaires présents dans la médiathèque
			$realStock = $this->getStockableMediaCopies()->count();
			if ($stock > $realStock){
				// Cas où l'on ajoute 1 ou plusieurs exemplaires (car la valeur de stock a augmenté)

				// D'abord, récupérer l'objet d'instance "MediaState" correspondant à l'ID 3 (état neuf)
				$media_state = $this->getMedia()->getDoctrine()->getRepository(StateOfMedia::class)->findOneBy(['id' => 3]);
				// Ajout de la différence de média
				for ($i = $realStock + 1; $i <= $stock; $i++){
					$newStockableMediaCopy = new StockableMediaCopy();
					// La propriété "copy_number" définit donc le numéro de l'exemplaire
					$newStockableMediaCopy->setCopyNumber($i);
					// cette nouvelle instance de "StockableMediaCopy" est liée l'instance "$this" de "StockableMedia"
					$newStockableMediaCopy->setStockableMedia($this);
					// Enfin, on applique l'état "neuf" à cette nouvelle copie, considérant que par défaut, un nouvel exemplaire est présupposément neuf
					$newStockableMediaCopy->setMediaState($media_state);
					// Utilisation de l'entity manager de l'objet "doctrine" qui a été injecté (injection de dépendance) dans l'objet "Media"
					$doctrine->getManager()->persist($newStockableMediaCopy);
					$this->addStockableMediaCopy($newStockableMediaCopy);
				}
			} elseif ($stock < $realStock){
				foreach ($this->getStockableMediaCopies() as $stockableMediaCopy){
					if ($stockableMediaCopy->getCopyNumber() > $stock)
						$this->removeStockableMediaCopy($stockableMediaCopy);
				}
			}
		}
		$this->stock = $stock;

		return $this;
	}

	public function getReceptionDate(): ?\DateTimeInterface{
		return $this->reception_date;
	}

	public function setReceptionDate(?\DateTimeInterface $reception_date): self{
		$this->reception_date = $reception_date;

		return $this;
	}

	public function getMedia(): ?Media{
		return $this->media;
	}

	public function setMedia(Media $media): self{
		$this->media = $media;

		return $this;
	}

	/**
	 *
	 * @return Collection|StockableMediaCopy[]
	 */
	public function getStockableMediaCopies(): Collection{
		return $this->stockableMediaCopies;
	}

	public function addStockableMediaCopy(StockableMediaCopy $stockableMediaCopy): self{
		if (!$this->stockableMediaCopies->contains($stockableMediaCopy)){
			$this->stockableMediaCopies[] = $stockableMediaCopy;
			$stockableMediaCopy->setStockableMedia($this);
		}

		return $this;
	}

	public function removeStockableMediaCopy(StockableMediaCopy $stockableMediaCopy): self{
		if ($this->stockableMediaCopies->contains($stockableMediaCopy)){
			$this->stockableMediaCopies->removeElement($stockableMediaCopy);
			// set the owning side to null (unless already changed)
			if ($stockableMediaCopy->getStockableMedia() === $this){
				$stockableMediaCopy->setStockableMedia(null);
			}
		}

		return $this;
	}
}
