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

    #[ORM\Column(type: 'date')]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: 'date')]
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

    public function __construct()
    {
        $this->semainesFermeture = new ArrayCollection();
        $this->adhesions = new ArrayCollection();
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
}
