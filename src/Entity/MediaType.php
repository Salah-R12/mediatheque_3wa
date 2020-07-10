<?php

namespace App\Entity;

use App\Repository\MediaTypeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaTypeRepository::class)
 */
class MediaType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $borrow_duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBorrowDuration(): ?int
    {
        return $this->borrow_duration;
    }

    public function setBorrowDuration(int $borrow_duration): self
    {
        $this->borrow_duration = $borrow_duration;

        return $this;
    }
}
