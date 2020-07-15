<?php

namespace App\Entity;

use App\Repository\StreamRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StreamRepository::class)
 */
class Stream
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=DigitalMedia::class, inversedBy="streams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $digital_media;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="streams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDigitalMedia(): ?DigitalMedia
    {
        return $this->digital_media;
    }

    public function setDigitalMedia(?DigitalMedia $digital_media): self
    {
        $this->digital_media = $digital_media;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }
}
