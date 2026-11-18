<?php

namespace App\Entity;

use App\Repository\SemaineFermetureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemaineFermetureRepository::class)]
class SemaineFermeture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $numeroSemaine = null;

    #[ORM\Column(type: 'integer')]
    private ?int $annee = null;

    #[ORM\ManyToOne(inversedBy: 'semainesFermeture')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Saison $saison = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroSemaine(): ?int
    {
        return $this->numeroSemaine;
    }

    public function setNumeroSemaine(int $numeroSemaine): static
    {
        $this->numeroSemaine = $numeroSemaine;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

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
}
