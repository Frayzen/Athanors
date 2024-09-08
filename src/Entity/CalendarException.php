<?php

namespace App\Entity;

use App\Repository\CalendarExceptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CalendarExceptionRepository::class)
 */
class CalendarException
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="start", message="Veuillez choisir les dates dans le bon ordre")
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="calendarExceptions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pro;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getPro(): ?Professional
    {
        return $this->pro;
    }

    public function setPro(?Professional $pro): self
    {
        $this->pro = $pro;

        return $this;
    }
}
