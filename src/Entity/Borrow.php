<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BorrowRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Borrow
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class, inversedBy="borrows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity=StockableMediaCopy::class, inversedBy="borrows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $stockable_media_copy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $borrow_date;
    public function __construct()
    {
        $this->borrow_date = new \DateTime();
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiry_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $return_date;

    /**
     * @ORM\ManyToOne(targetEntity=StateOfMedia::class)
     */
    private $return_media_state;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStockableMediaCopy(): ?StockableMediaCopy
    {
        return $this->stockable_media_copy;
    }

    public function setStockableMediaCopy(?StockableMediaCopy $stockable_media_copy): self
    {
        $this->stockable_media_copy = $stockable_media_copy;

        return $this;
    }

    public function getBorrowDate(): ?\DateTimeInterface
    {
        return $this->borrow_date;
    }

    public function setBorrowDate(\DateTimeInterface $borrow_date): self
    {
        $this->borrow_date = $borrow_date;

        return $this;
    }

    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->expiry_date;
    }

    public function setExpiryDate(\DateTimeInterface $expiry_date): self
    {
        $this->expiry_date = $expiry_date;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(?\DateTimeInterface $return_date): self
    {
        $this->return_date = $return_date;

        return $this;
    }

    public function getReturnMediaState(): ?StateOfMedia
    {
        return $this->return_media_state;
    }

    public function setReturnMediaState(?StateOfMedia $return_media_state): self
    {
        $this->return_media_state = $return_media_state;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function calculateExpiryDate(): self
    {
        // Expiry date must be pre-defined (on insert only) by the day numbers set to media type
        // e.g. if media type is book, and borrow duration is 30 days, then expiry date must be borrow date + 30 days

        // Get borrow duration
        $borrowDuration = $this->getStockableMediaCopy()->getStockableMedia()->getMedia()->getMediaType()->getBorrowDuration();
        $expiryDateTime = new \DateTime($this->getBorrowDate()->getTimestamp());
        $expiryDateTime->add(new \DateInterval("P${borrowDuration}D"));
        return $this->setExpiryDate($expiryDateTime);
    }

    public function __toString()
    {
        return $this->id;
    }
}