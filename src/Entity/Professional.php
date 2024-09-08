<?php

namespace App\Entity;

use App\Repository\ProfessionalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfessionalRepository::class)
 */
class Professional
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="pro", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=WorkTime::class, mappedBy="pro")
     */
    private $workTimes;

    /**
     * @ORM\OneToMany(targetEntity=Rdv::class, mappedBy="pro")
     */
    private $rdvs;

    /**
     * @ORM\OneToMany(targetEntity=Overtime::class, mappedBy="pro")
     */
    private $overtimes;

    /**
     * @ORM\OneToMany(targetEntity=CalendarException::class, mappedBy="pro")
     */
    private $calendarExceptions;

    /**
     * @ORM\OneToMany(targetEntity=CancelledRdv::class, mappedBy="pro")
     */
    private $cancelledRdvs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $job;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $citation;

    /**
     * @ORM\Column(type="string", length=4096)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $use_agenda;

    /**
     * @ORM\Column(type="boolean")
     */
    private $can_manage_atelier;

    /**
     * @ORM\Column(type="text")
     */
    private $pageContent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageFile;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canManageSupervisors;

    public function __construct()
    {
        $this->workTimes = new ArrayCollection();
        $this->rdvs = new ArrayCollection();
        $this->overtimes = new ArrayCollection();
        $this->calendarExceptions = new ArrayCollection();
        $this->cancelledRdvs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|WorkTime[]
     */
    public function getWorkTimes(): Collection
    {
        return $this->workTimes;
    }

    public function addWorkTime(WorkTime $workTime): self
    {
        if (!$this->workTimes->contains($workTime)) {
            $this->workTimes[] = $workTime;
            $workTime->setPro($this);
        }

        return $this;
    }

    public function removeWorkTime(WorkTime $workTime): self
    {
        if ($this->workTimes->contains($workTime)) {
            $this->workTimes->removeElement($workTime);
            // set the owning side to null (unless already changed)
            if ($workTime->getPro() === $this) {
                $workTime->setPro(null);
            }
        }

        return $this;
    }


    /**
     * @return array
     */
    public function getRdvNotViewed(){
        $a = [];
        foreach ($this->getRdvs() as $crdv) {
            if (!$crdv->getViewed() && !$crdv->getValidate() && $crdv->getStart() > new \DateTime())
                $a[] = $crdv;
        }
        return $a;
    }

    /**
     * @return Collection|Rdv[]
     */
    public function getRdvs(): Collection
    {
        return $this->rdvs;
    }

    public function addRdv(Rdv $rdv): self
    {
        if (!$this->rdvs->contains($rdv)) {
            $this->rdvs[] = $rdv;
            $rdv->setPro($this);
        }

        return $this;
    }

    public function removeRdv(Rdv $rdv): self
    {
        if ($this->rdvs->contains($rdv)) {
            $this->rdvs->removeElement($rdv);
            // set the owning side to null (unless already changed)
            if ($rdv->getPro() === $this) {
                $rdv->setPro(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Overtime[]
     */
    public function getOvertimes(): Collection
    {
        return $this->overtimes;
    }

    public function addOvertime(Overtime $overtime): self
    {
        if (!$this->overtimes->contains($overtime)) {
            $this->overtimes[] = $overtime;
            $overtime->setPro($this);
        }

        return $this;
    }

    public function removeOvertime(Overtime $overtime): self
    {
        if ($this->overtimes->contains($overtime)) {
            $this->overtimes->removeElement($overtime);
            // set the owning side to null (unless already changed)
            if ($overtime->getPro() === $this) {
                $overtime->setPro(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CalendarException[]
     */
    public function getCalendarExceptions(): Collection
    {
        return $this->calendarExceptions;
    }

    public function addCalendarException(CalendarException $calendarException): self
    {
        if (!$this->calendarExceptions->contains($calendarException)) {
            $this->calendarExceptions[] = $calendarException;
            $calendarException->setPro($this);
        }

        return $this;
    }

    public function removeCalendarException(CalendarException $calendarException): self
    {
        if ($this->calendarExceptions->contains($calendarException)) {
            $this->calendarExceptions->removeElement($calendarException);
            // set the owning side to null (unless already changed)
            if ($calendarException->getPro() === $this) {
                $calendarException->setPro(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CancelledRdv[]
     */
    public function getCancelledRdvs(): Collection
    {
        return $this->cancelledRdvs;
    }

    public function addCancelledRdv(CancelledRdv $cancelledRdv): self
    {
        if (!$this->cancelledRdvs->contains($cancelledRdv)) {
            $this->cancelledRdvs[] = $cancelledRdv;
            $cancelledRdv->setPro($this);
        }

        return $this;
    }

    public function removeCancelledRdv(CancelledRdv $cancelledRdv): self
    {
        if ($this->cancelledRdvs->contains($cancelledRdv)) {
            $this->cancelledRdvs->removeElement($cancelledRdv);
            // set the owning side to null (unless already changed)
            if ($cancelledRdv->getPro() === $this) {
                $cancelledRdv->setPro(null);
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getAskCancelNotViewed(){
        $a = [];
        foreach ($this->getRdvs() as $crdv) {
            if ($crdv->getAskCancel() != null and !$crdv->getAskCancel()->getViewed())
                $a[] = $crdv;
        }
        return $a;
    }


    /**
     * @return array
     */
    public function getAskCancels(){
        $a = [];
        foreach ($this->getRdvs() as $crdv) {
            if ($crdv->getAskCancel() != null)
                $a[] = $crdv;
        }
        return $a;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getCitation(): ?string
    {
        return $this->citation;
    }

    public function setCitation(string $citation): self
    {
        $this->citation = $citation;

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

    public function getUseAgenda(): ?bool
    {
        return $this->use_agenda;
    }

    public function setUseAgenda(bool $use_agenda): self
    {
        $this->use_agenda = $use_agenda;

        return $this;
    }

    public function getCanManageAtelier(): ?bool
    {
        return $this->can_manage_atelier;
    }

    public function setCanManageAtelier(bool $can_manage_atelier): self
    {
        $this->can_manage_atelier = $can_manage_atelier;

        return $this;
    }

    public function getPageContent(): ?string
    {
        return $this->pageContent;
    }

    public function setPageContent(string $pageContent): self
    {
        $this->pageContent = $pageContent;

        return $this;
    }

    public function getImageFile(): ?string
    {
        return $this->imageFile;
    }

    public function setImageFile(string $imageFile): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }

    public function getCanManageSupervisors(): ?bool
    {
        return $this->canManageSupervisors;
    }

    public function setCanManageSupervisors(bool $canManageSupervisors): self
    {
        $this->canManageSupervisors = $canManageSupervisors;

        return $this;
    }
}
