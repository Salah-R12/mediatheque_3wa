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
		// There is event on set stock because it will update the lines of copies into table "StockableMediaCopy"
		if ($doctrine = $this->getMedia()->getDoctrine()){
			$realStock = $this->getStockableMediaCopies()->count();
			if ($stock > $realStock){
				$stockDiff = $stock - $realStock;
				$media_state = $this->getMedia()->getDoctrine()->getRepository(StateOfMedia::class)->findOneBy(['id' => 3]);
				for ($i = $realStock; $i <= $stockDiff; $i++){
					$newStockableMediaCopy = new StockableMediaCopy();
					$newStockableMediaCopy->setCopyNumber($realStock + $i);
					$newStockableMediaCopy->setStockableMedia($this);
					$newStockableMediaCopy->setMediaState($media_state);
					$doctrine->getManager()->persist($newStockableMediaCopy);
					$this->addStockableMediaCopy($newStockableMediaCopy);
				}
			} elseif ($stock < $realStock){
				foreach ($this->getStockableMediaCopies() as $stockableMediaCopy){
					if ($stockableMediaCopy->getCopyNumber() > $realStock)
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
