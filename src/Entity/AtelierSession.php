<?php

namespace App\Entity;

use App\Repository\AtelierSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AtelierSessionRepository::class)
 */
class AtelierSession
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Atelier::class, inversedBy="atelierSessions")
     */
    private $atelier;

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
    private $calendarId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $themes;

    /**
     * @ORM\OneToMany(targetEntity=AnswerUserSession::class, mappedBy="session")
     */
    private $answerUserSessions;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $delayAnswer;

    public function __construct()
    {
        $this->answerUserSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

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
        return $this->calendarId;
    }

    public function setCalendarId(int $calendarId): self
    {
        $this->calendarId = $calendarId;

        return $this;
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

    public function getAtelier(): ?Atelier
    {
        return $this->atelier;
    }

    public function setAtelier(Atelier $atelier): self
    {
        $this->atelier = $atelier;

        return $this;
    }

    public function getThemes(): ?string
    {
        return $this->themes;
    }

    public function setThemes(string $themes): self
    {
        $this->themes = $themes;

        return $this;
    }

    /**
     * @return Collection|AnswerUserSession[]
     */
    public function getAnswerUserSessions(): Collection
    {
        return $this->answerUserSessions;
    }

    public function addAnswerUserSession(AnswerUserSession $answerUserSession): self
    {
        if (!$this->answerUserSessions->contains($answerUserSession)) {
            $this->answerUserSessions[] = $answerUserSession;
            $answerUserSession->setSession($this);
        }

        return $this;
    }

    public function removeAnswerUserSession(AnswerUserSession $answerUserSession): self
    {
        if ($this->answerUserSessions->removeElement($answerUserSession)) {
            // set the owning side to null (unless already changed)
            if ($answerUserSession->getSession() === $this) {
                $answerUserSession->setSession(null);
            }
        }

        return $this;
    }

    public function getDelayAnswer(): ?\DateTimeInterface
    {
        return $this->delayAnswer;
    }

    public function setDelayAnswer(\DateTimeInterface $delayAnswer): self
    {
        $this->delayAnswer = $delayAnswer;

        return $this;
    }
}
