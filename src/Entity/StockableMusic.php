<?php

namespace App\Entity;

use App\Repository\StockableMusicRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockableMusicRepository::class)
 */
class StockableMusic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $stock;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $reception_date;

    /**
     * @ORM\OneToOne(targetEntity=Music::class, inversedBy="stockableMusic", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $media_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getReceptionDate(): ?\DateTimeInterface
    {
        return $this->reception_date;
    }

    public function setReceptionDate(?\DateTimeInterface $reception_date): self
    {
        $this->reception_date = $reception_date;

        return $this;
    }

    public function getMediaId(): ?Music
    {
        return $this->media_id;
    }

    public function setMediaId(Music $media_id): self
    {
        $this->media_id = $media_id;

        return $this;
    }
}
