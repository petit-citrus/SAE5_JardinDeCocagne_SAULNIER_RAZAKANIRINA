<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaisonRepository::class)]
class Saison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: 'date_immutable')]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: 'date_immutable')]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column]
    private ?bool $cloture = null;

    #[ORM\ManyToOne(inversedBy: 'saisons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    /**
     * @var Collection<int, SemaineFermeture>
     */
    #[ORM\OneToMany(targetEntity: SemaineFermeture::class, mappedBy: 'saison')]
    private Collection $semainesFermeture;

    /**
     * @var Collection<int, Adhesion>
     */
    #[ORM\OneToMany(targetEntity: Adhesion::class, mappedBy: 'saison')]
    private Collection $adhesions;

    /**
     * @var Collection<int, DecalageJourFerie>
     */
    #[ORM\OneToMany(targetEntity: DecalageJourFerie::class, mappedBy: 'saison')]
    private Collection $decalagesJoursFeries;

    /**
     * @var Collection<int, CalendrierDiffusion>
     */
    #[ORM\OneToMany(targetEntity: CalendrierDiffusion::class, mappedBy: 'saison')]
    private Collection $calendriersDiffusion;

    /**
     * @var Collection<int, CompositionPanier>
     */
    #[ORM\OneToMany(targetEntity: CompositionPanier::class, mappedBy: 'saison')]
    private Collection $compositionsPaniers;

    /**
     * @var Collection<int, AbonnementClient>
     */
    #[ORM\OneToMany(targetEntity: AbonnementClient::class, mappedBy: 'saison')]
    private Collection $abonnementsClients;

    /**
     * @var Collection<int, FeuilleDeChou>
     */
    #[ORM\OneToMany(targetEntity: FeuilleDeChou::class, mappedBy: 'saison')]
    private Collection $feuillesDeChou;

    public function __construct()
    {
        $this->semainesFermeture = new ArrayCollection();
        $this->adhesions = new ArrayCollection();
        $this->decalagesJoursFeries = new ArrayCollection();
        $this->calendriersDiffusion = new ArrayCollection();
        $this->compositionsPaniers = new ArrayCollection();
        $this->abonnementsClients = new ArrayCollection();
        $this->feuillesDeChou = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function isCloture(): ?bool
    {
        return $this->cloture;
    }

    public function setCloture(bool $cloture): static
    {
        $this->cloture = $cloture;

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

    /**
     * @return Collection<int, SemaineFermeture>
     */
    public function getSemainesFermeture(): Collection
    {
        return $this->semainesFermeture;
    }

    public function addSemaineFermeture(SemaineFermeture $semaineFermeture): static
    {
        if (!$this->semainesFermeture->contains($semaineFermeture)) {
            $this->semainesFermeture->add($semaineFermeture);
            $semaineFermeture->setSaison($this);
        }

        return $this;
    }

    public function removeSemaineFermeture(SemaineFermeture $semaineFermeture): static
    {
        if ($this->semainesFermeture->removeElement($semaineFermeture)) {
            if ($semaineFermeture->getSaison() === $this) {
                $semaineFermeture->setSaison(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adhesion>
     */
    public function getAdhesions(): Collection
    {
        return $this->adhesions;
    }

    public function addAdhesion(Adhesion $adhesion): static
    {
        if (!$this->adhesions->contains($adhesion)) {
            $this->adhesions->add($adhesion);
            $adhesion->setSaison($this);
        }

        return $this;
    }

    public function removeAdhesion(Adhesion $adhesion): static
    {
        if ($this->adhesions->removeElement($adhesion)) {
            if ($adhesion->getSaison() === $this) {
                $adhesion->setSaison(null);
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
            $decalage->setSaison($this);
        }

        return $this;
    }

    public function removeDecalageJourFerie(DecalageJourFerie $decalage): static
    {
        if ($this->decalagesJoursFeries->removeElement($decalage)) {
            if ($decalage->getSaison() === $this) {
                $decalage->setSaison(null);
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
            $calendrier->setSaison($this);
        }

        return $this;
    }

    public function removeCalendrierDiffusion(CalendrierDiffusion $calendrier): static
    {
        if ($this->calendriersDiffusion->removeElement($calendrier)) {
            if ($calendrier->getSaison() === $this) {
                $calendrier->setSaison(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CompositionPanier>
     */
    public function getCompositionsPaniers(): Collection
    {
        return $this->compositionsPaniers;
    }

    public function addCompositionPanier(CompositionPanier $compositionPanier): static
    {
        if (!$this->compositionsPaniers->contains($compositionPanier)) {
            $this->compositionsPaniers->add($compositionPanier);
            $compositionPanier->setSaison($this);
        }

        return $this;
    }

    public function removeCompositionPanier(CompositionPanier $compositionPanier): static
    {
        if ($this->compositionsPaniers->removeElement($compositionPanier)) {
            if ($compositionPanier->getSaison() === $this) {
                $compositionPanier->setSaison(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AbonnementClient>
     */
    public function getAbonnementsClients(): Collection
    {
        return $this->abonnementsClients;
    }

    public function addAbonnementClient(AbonnementClient $abonnement): static
    {
        if (!$this->abonnementsClients->contains($abonnement)) {
            $this->abonnementsClients->add($abonnement);
            $abonnement->setSaison($this);
        }

        return $this;
    }

    public function removeAbonnementClient(AbonnementClient $abonnement): static
    {
        if ($this->abonnementsClients->removeElement($abonnement)) {
            if ($abonnement->getSaison() === $this) {
                $abonnement->setSaison(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FeuilleDeChou>
     */
    public function getFeuillesDeChou(): Collection
    {
        return $this->feuillesDeChou;
    }

    public function addFeuilleDeChou(FeuilleDeChou $feuille): static
    {
        if (!$this->feuillesDeChou->contains($feuille)) {
            $this->feuillesDeChou->add($feuille);
            $feuille->setSaison($this);
        }

        return $this;
    }

    public function removeFeuilleDeChou(FeuilleDeChou $feuille): static
    {
        if ($this->feuillesDeChou->removeElement($feuille)) {
            if ($feuille->getSaison() === $this) {
                $feuille->setSaison(null);
            }
        }

        return $this;
    }
}
