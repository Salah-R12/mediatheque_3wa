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

    public function getPageNb(): ?int
    {
        return $this->page_nb;
    }

    public function setPageNb(int $page_nb): self
    {
        $this->page_nb = $page_nb;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
