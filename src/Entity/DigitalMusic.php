<?php

namespace App\Entity;

use App\Repository\DigitalMusicRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DigitalMusicRepository::class)
 */
class DigitalMusic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $path;

    /**
     * @ORM\OneToOne(targetEntity=Music::class, inversedBy="digitalMusic", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $media_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

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
