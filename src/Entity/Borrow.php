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
     * @ORM\Column(type="datetime")
     */
    private $borrow_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $return_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expected_date;

    /**
     * @ORM\ManyToOne(targetEntity=Member::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $member_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(?\DateTimeInterface $return_date): self
    {
        $this->return_date = $return_date;

        return $this;
    }

    public function getExpectedDate(): ?\DateTimeInterface
    {
        return $this->expected_date;
    }

    public function setExpectedDate(?\DateTimeInterface $expected_date): self
    {
        $this->expected_date = $expected_date;

        return $this;
    }

    public function getMemberId(): ?Member
    {
        return $this->member_id;
    }

    public function setMemberId(?Member $member_id): self
    {
        $this->member_id = $member_id;

        return $this;
    }
}
