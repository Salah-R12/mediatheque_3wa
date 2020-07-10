<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="media_type_id", type="integer")
 * @ORM\DiscriminatorMap({"0" = "Media", "1" = "Book", "2" = "Film", "3" = "Music"})
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    protected $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity=MediaType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    protected $media_type_id;

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

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMediaTypeId(): ?MediaType
    {
        return $this->media_type_id;
    }

    public function setMediaTypeId(?MediaType $media_type_id): self
    {
        $this->media_type_id = $media_type_id;

        return $this;
    }
}
