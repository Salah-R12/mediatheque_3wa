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
}
