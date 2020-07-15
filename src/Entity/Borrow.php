<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BorrowRepository::class)
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
}