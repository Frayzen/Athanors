<?php

namespace App\Entity;

use App\Repository\AtelierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

/**
 * @ORM\Entity(repositoryClass=AtelierRepository::class)
 */
class Atelier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan (propertyPath="start")
     */
    private $end;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="ateliers")
     */
    private $members;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_user;

    /**
     * @ORM\Column(type="float")
     */
    private $price_per_session;

    /**
     * @ORM\OneToMany(targetEntity=AtelierSession::class, mappedBy="atelier")
     */
    private $atelierSessions;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sessionsMandatory;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canJoinAfterStart;

    /**
     * @ORM\ManyToMany(targetEntity=Supervisor::class, inversedBy="ateliers")
     */
    private $supervisors;

    public function __construct()
    {
        $this->members = new ArrayCollection();
        $this->atelierSessions = new ArrayCollection();
        $this->supervisors = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(User $member): self
    {
        $this->members->removeElement($member);

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getMaxUser(): ?int
    {
        return $this->max_user;
    }

    public function setMaxUser(int $max_user): self
    {
        $this->max_user = $max_user;

        return $this;
    }

    public function getPricePerSession(): ?float
    {
        return $this->price_per_session;
    }

    public function setPricePerSession(float $price_per_session): self
    {
        $this->price_per_session = $price_per_session;

        return $this;
    }

    /**
     * @return Collection|AtelierSession[]
     */
    public function getAtelierSessions(): Collection
    {
        return $this->atelierSessions;
    }

    public function addAtelierSession(AtelierSession $atelierSession): self
    {
        if (!$this->atelierSessions->contains($atelierSession)) {
            $this->atelierSessions[] = $atelierSession;
            $atelierSession->setAtelier($this);
        }

        return $this;
    }

    public function removeAtelierSession(AtelierSession $atelierSession): self
    {
        if ($this->atelierSessions->removeElement($atelierSession)) {
            // set the owning side to null (unless already changed)
            if ($atelierSession->getAtelier() === $this) {
                $atelierSession->setAtelier(null);
            }
        }

        return $this;
    }

    public function getDataCalendar($user){
        $datas = [];
        foreach ($this->getAtelierSessions() as $session) {
            $answer = null;
            $textColor = null;
            if ($user != null) {
                foreach ($session->getAnswerUserSessions() as $answers) {
                    if ($answers->getUser() == $user && $answers->getSession() == $session) {
                        $answer = $answers->getPresence();
                        $textColor = $answer ? "#28a745" : "#dc3545";
                    }
                }
            }
            $datas[] = [
                'id'=>$session->getCalendarId(),
                'start'=>$session->getStart()->format("Y-m-d H:i"),
                'end'=>$session->getEnd()->format("Y-m-d H:i") ,
                'title'=>$session->getName() == null ? "" : $session->getName(),
                'themes'=>$session->getThemes(),
                'answer'=>$answer,
                'textColor'=>$textColor,
            ];
        }
        return json_encode($datas);
    }

    public function getSessionsMandatory(): ?bool
    {
        return $this->sessionsMandatory;
    }

    public function setSessionsMandatory(bool $sessionsMandatory): self
    {
        $this->sessionsMandatory = $sessionsMandatory;

        return $this;
    }

    public function getCanJoinAfterStart(): ?bool
    {
        return $this->canJoinAfterStart;
    }

    public function setCanJoinAfterStart(bool $canJoinAfterStart): self
    {
        $this->canJoinAfterStart = $canJoinAfterStart;

        return $this;
    }

    public function isAccesible(): bool
    {
        if (($this->getStart() < new \DateTime() && !$this->canJoinAfterStart) || count($this->getMembers()) >= $this->getMaxUser())
            return false;
        return true;
    }

    /**
     * @return Collection|Supervisor[]
     */
    public function getSupervisors(): Collection
    {
        return $this->supervisors;
    }

    public function addSupervisor(Supervisor $supervisor): self
    {
        if (!$this->supervisors->contains($supervisor)) {
            $this->supervisors[] = $supervisor;
        }

        return $this;
    }

    public function removeSupervisor(Supervisor $supervisor): self
    {
        $this->supervisors->removeElement($supervisor);

        return $this;
    }
}
