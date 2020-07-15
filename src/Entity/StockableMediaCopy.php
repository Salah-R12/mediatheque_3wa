<?php

namespace App\Entity;

use App\Repository\StockableMediaCopyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockableMediaCopyRepository::class)
 */
class StockableMediaCopy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $copy_number;

    /**
     * @ORM\ManyToOne(targetEntity=StockableMedia::class, inversedBy="stockableMediaCopies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stockable_media;

    /**
     * @ORM\ManyToOne(targetEntity=StateOfMedia::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $media_state;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCopyNumber(): ?int
    {
        return $this->copy_number;
    }

    public function setCopyNumber(int $copy_number): self
    {
        $this->copy_number = $copy_number;

        return $this;
    }

    public function getStockableMedia(): ?StockableMedia
    {
        return $this->stockable_media;
    }

    public function setStockableMedia(?StockableMedia $stockable_media): self
    {
        $this->stockable_media = $stockable_media;

        return $this;
    }

    public function getMediaState(): ?StateOfMedia
    {
        return $this->media_state;
    }

    public function setMediaState(?StateOfMedia $media_state): self
    {
        $this->media_state = $media_state;

        return $this;
    }
}
