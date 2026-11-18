<?php

namespace App\Entity;

use App\Repository\CalendrierDiffusionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendrierDiffusionRepository::class)]
class CalendrierDiffusion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $rruleComplete = null;

    #[ORM\Column(length: 255)]
    private ?string $pariteDepart = null;

    #[ORM\Column(type: 'integer')]
    private ?int $nbPaniers = null;

    #[ORM\ManyToOne(inversedBy: 'calendriersDiffusion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Saison $saison = null;

    #[ORM\ManyToOne(inversedBy: 'calendriersDiffusion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournee $tournee = null;

    #[ORM\ManyToOne(inversedBy: 'calendriersDiffusion')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Frequence $frequence = null;

    /**
     * @var Collection<int, ExceptionCalendrier>
     */
    #[ORM\OneToMany(targetEntity: ExceptionCalendrier::class, mappedBy: 'calendrierDiffusion')]
    private Collection $exceptionsCalendrier;

    public function __construct()
    {
        $this->exceptionsCalendrier = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRruleComplete(): ?string
    {
        return $this->rruleComplete;
    }

    public function setRruleComplete(string $rruleComplete): static
    {
        $this->rruleComplete = $rruleComplete;

        return $this;
    }

    public function getPariteDepart(): ?string
    {
        return $this->pariteDepart;
    }

    public function setPariteDepart(string $pariteDepart): static
    {
        $this->pariteDepart = $pariteDepart;

        return $this;
    }

    public function getNbPaniers(): ?int
    {
        return $this->nbPaniers;
    }

    public function setNbPaniers(int $nbPaniers): static
    {
        $this->nbPaniers = $nbPaniers;

        return $this;
    }

    public function getSaison(): ?Saison
    {
        return $this->saison;
    }

    public function setSaison(?Saison $saison): static
    {
        $this->saison = $saison;

        return $this;
    }

    public function getTournee(): ?Tournee
    {
        return $this->tournee;
    }

    public function setTournee(?Tournee $tournee): static
    {
        $this->tournee = $tournee;

        return $this;
    }

    public function getFrequence(): ?Frequence
    {
        return $this->frequence;
    }

    public function setFrequence(?Frequence $frequence): static
    {
        $this->frequence = $frequence;

        return $this;
    }

    /**
     * @return Collection<int, ExceptionCalendrier>
     */
    public function getExceptionsCalendrier(): Collection
    {
        return $this->exceptionsCalendrier;
    }

    public function addExceptionCalendrier(ExceptionCalendrier $exceptionCalendrier): static
    {
        if (!$this->exceptionsCalendrier->contains($exceptionCalendrier)) {
            $this->exceptionsCalendrier->add($exceptionCalendrier);
            $exceptionCalendrier->setCalendrierDiffusion($this);
        }

        return $this;
    }

    public function removeExceptionCalendrier(ExceptionCalendrier $exceptionCalendrier): static
    {
        if ($this->exceptionsCalendrier->removeElement($exceptionCalendrier)) {
            if ($exceptionCalendrier->getCalendrierDiffusion() === $this) {
                $exceptionCalendrier->setCalendrierDiffusion(null);
            }
        }

        return $this;
    }
}
