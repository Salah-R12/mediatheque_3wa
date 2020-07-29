<?php

namespace App\Entity;

use App\Repository\StockableMediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StockableMediaRepository::class)
 */
class StockableMedia{

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 * @Assert\PositiveOrZero(
	 * 		message="Le nombre d'exemplaires doit être un entier positif"
	 * )
	 */
	private $stock;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $reception_date;

	/**
	 * @ORM\OneToOne(targetEntity=Media::class, inversedBy="stockableMedia")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $media;

	/**
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
		// Stock cannot be negative
		if ($stock < 0)
			$stock = 0;
		
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
    
    public function __toString(): string{
    	return $this->getMedia()->getMediaType()->getName() . ' : ' . $this->getMedia()->getName();
    }
}
