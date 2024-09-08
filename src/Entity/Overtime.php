<?php

namespace App\Entity;

use App\Repository\OvertimeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OvertimeRepository::class)
 */
class Overtime
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Professional::class, inversedBy="overtimes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pro;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(type="integer")
     */
    private $calendar_id;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCalendarId(): ?int
    {
        return $this->calendar_id;
    }

    public function setCalendarId(int $calendar_id): self
    {
        $this->calendar_id = $calendar_id;

        return $this;
    }
}
