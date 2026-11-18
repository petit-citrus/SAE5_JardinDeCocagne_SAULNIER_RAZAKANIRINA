<?php

namespace App\Entity;

use App\Repository\TourneeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourneeRepository::class)]
class Tournee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $identifiant = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    #[ORM\ManyToOne(inversedBy: 'tournees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    #[ORM\ManyToOne(inversedBy: 'tournees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JourPreparation $jourPreparation = null;

    /**
     * @var Collection<int, TourneePointDepot>
     */
    #[ORM\OneToMany(targetEntity: TourneePointDepot::class, mappedBy: 'tournee')]
    private Collection $tourneePointsDepot;

    /**
     * @var Collection<int, DecalageJourFerie>
     */
    #[ORM\OneToMany(targetEntity: DecalageJourFerie::class, mappedBy: 'tournee')]
    private Collection $decalagesJoursFeries;

    /**
     * @var Collection<int, CalendrierDiffusion>
     */
    #[ORM\OneToMany(targetEntity: CalendrierDiffusion::class, mappedBy: 'tournee')]
    private Collection $calendriersDiffusion;

    public function __construct()
    {
        $this->tourneePointsDepot = new ArrayCollection();
        $this->decalagesJoursFeries = new ArrayCollection();
        $this->calendriersDiffusion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): static
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): static
    {
        $this->structure = $structure;

        return $this;
    }

    public function getJourPreparation(): ?JourPreparation
    {
        return $this->jourPreparation;
    }

    public function setJourPreparation(?JourPreparation $jourPreparation): static
    {
        $this->jourPreparation = $jourPreparation;

        return $this;
    }

    /**
     * @return Collection<int, TourneePointDepot>
     */
    public function getTourneePointsDepot(): Collection
    {
        return $this->tourneePointsDepot;
    }

    public function addTourneePointDepot(TourneePointDepot $pointDepot): static
    {
        if (!$this->tourneePointsDepot->contains($pointDepot)) {
            $this->tourneePointsDepot->add($pointDepot);
            $pointDepot->setTournee($this);
        }

        return $this;
    }

    public function removeTourneePointDepot(TourneePointDepot $pointDepot): static
    {
        if ($this->tourneePointsDepot->removeElement($pointDepot)) {
            if ($pointDepot->getTournee() === $this) {
                $pointDepot->setTournee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DecalageJourFerie>
     */
    public function getDecalagesJoursFeries(): Collection
    {
        return $this->decalagesJoursFeries;
    }

    public function addDecalageJourFerie(DecalageJourFerie $decalage): static
    {
        if (!$this->decalagesJoursFeries->contains($decalage)) {
            $this->decalagesJoursFeries->add($decalage);
            $decalage->setTournee($this);
        }

        return $this;
    }

    public function removeDecalageJourFerie(DecalageJourFerie $decalage): static
    {
        if ($this->decalagesJoursFeries->removeElement($decalage)) {
            if ($decalage->getTournee() === $this) {
                $decalage->setTournee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CalendrierDiffusion>
     */
    public function getCalendriersDiffusion(): Collection
    {
        return $this->calendriersDiffusion;
    }

    public function addCalendrierDiffusion(CalendrierDiffusion $calendrier): static
    {
        if (!$this->calendriersDiffusion->contains($calendrier)) {
            $this->calendriersDiffusion->add($calendrier);
            $calendrier->setTournee($this);
        }

        return $this;
    }

    public function removeCalendrierDiffusion(CalendrierDiffusion $calendrier): static
    {
        if ($this->calendriersDiffusion->removeElement($calendrier)) {
            if ($calendrier->getTournee() === $this) {
                $calendrier->setTournee(null);
            }
        }

        return $this;
    }
}
