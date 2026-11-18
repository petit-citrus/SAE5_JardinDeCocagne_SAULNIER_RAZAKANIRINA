<?php

namespace App\Entity;

use App\Repository\FrequenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FrequenceRepository::class)]
class Frequence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $rruleBase = null;

    #[ORM\ManyToOne(inversedBy: 'frequences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    /**
     * @var Collection<int, CalendrierDiffusion>
     */
    #[ORM\OneToMany(targetEntity: CalendrierDiffusion::class, mappedBy: 'frequence')]
    private Collection $calendriersDiffusion;

    public function __construct()
    {
        $this->calendriersDiffusion = new ArrayCollection();
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

    public function getRruleBase(): ?string
    {
        return $this->rruleBase;
    }

    public function setRruleBase(string $rruleBase): static
    {
        $this->rruleBase = $rruleBase;

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
            $calendrier->setFrequence($this);
        }

        return $this;
    }

    public function removeCalendrierDiffusion(CalendrierDiffusion $calendrier): static
    {
        if ($this->calendriersDiffusion->removeElement($calendrier)) {
            if ($calendrier->getFrequence() === $this) {
                $calendrier->setFrequence(null);
            }
        }

        return $this;
    }
}
