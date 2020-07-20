<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=MediaType::class, inversedBy="medias")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $media_type;

    /**
     * @ORM\OneToOne(targetEntity=DigitalMedia::class, mappedBy="media", cascade={"persist", "remove"})
     */
    private $digitalMedia;

    /**
     * @ORM\OneToOne(targetEntity=StockableMedia::class, mappedBy="media", cascade={"persist", "remove"})
     */
    private $stockableMedia;

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

    public function getMediaType(): ?MediaType
    {
        return $this->media_type;
    }

    public function setMediaType(?MediaType $media_type): self
    {
        $this->media_type = $media_type;

        return $this;
    }


    public function getDigitalMedia(): ?DigitalMedia
    {
        return $this->digitalMedia;
    }

    public function setDigitalMedia(DigitalMedia $digitalMedia): self
    {
        $this->digitalMedia = $digitalMedia;

        // set the owning side of the relation if necessary
        if ($digitalMedia->getMedia() !== $this) {
            $digitalMedia->setMedia($this);
        }

        return $this;
    }

    public function getStockableMedia(): ?StockableMedia
    {
        return $this->stockableMedia;
    }

    public function setStockableMedia(StockableMedia $stockableMedia): self
    {
        $this->stockableMedia = $stockableMedia;

        // set the owning side of the relation if necessary
        if ($stockableMedia->getMedia() !== $this) {
            $stockableMedia->setMedia($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
