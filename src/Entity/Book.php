<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book extends Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $edition;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $page_nb;

    /**
     * @ORM\OneToOne(targetEntity=DigitalBook::class, mappedBy="media_id", cascade={"persist", "remove"})
     */
    private $digitalBook;

    /**
     * @ORM\OneToOne(targetEntity=StockableBook::class, mappedBy="media_id", cascade={"persist", "remove"})
     */
    private $stockableBook;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEdition(): ?string
    {
        return $this->edition;
    }

    public function setEdition(string $edition): self
    {
        $this->edition = $edition;

        return $this;
    }

    public function getPublishDate(): ?\DateTimeInterface
    {
        return $this->publish_date;
    }

    public function setPublishDate(\DateTimeInterface $publish_date): self
    {
        $this->publish_date = $publish_date;

        return $this;
    }

    public function getPageNb(): ?int
    {
        return $this->page_nb;
    }

    public function setPageNb(int $page_nb): self
    {
        $this->page_nb = $page_nb;

        return $this;
    }

    public function getDigitalBook(): ?DigitalBook
    {
        return $this->digitalBook;
    }

    public function setDigitalBook(DigitalBook $digitalBook): self
    {
        $this->digitalBook = $digitalBook;

        // set the owning side of the relation if necessary
        if ($digitalBook->getMediaId() !== $this) {
            $digitalBook->setMediaId($this);
        }

        return $this;
    }

    public function getStockableBook(): ?StockableBook
    {
        return $this->stockableBook;
    }

    public function setStockableBook(StockableBook $stockableBook): self
    {
        $this->stockableBook = $stockableBook;

        // set the owning side of the relation if necessary
        if ($stockableBook->getMediaId() !== $this) {
            $stockableBook->setMediaId($this);
        }

        return $this;
    }
}
