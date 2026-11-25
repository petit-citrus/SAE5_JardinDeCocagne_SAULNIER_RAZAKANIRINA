<?php

namespace App\Entity;

use App\Repository\DecalageJourFerieRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DecalageJourFerieRepository::class)]
class DecalageJourFerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date_immutable')]
    private ?\DateTimeImmutable $dateFerie = null;

    #[ORM\Column(type: 'date_immutable')]
    private ?\DateTimeImmutable $dateReport = null;

    #[ORM\ManyToOne(inversedBy: 'decalagesJoursFeries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Saison $saison = null;

    #[ORM\ManyToOne(inversedBy: 'decalagesJoursFeries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournee $tournee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateFerie(): ?\DateTimeImmutable
    {
        return $this->dateFerie;
    }

    public function setDateFerie(\DateTimeImmutable $dateFerie): static
    {
        $this->dateFerie = $dateFerie;

        return $this;
    }

    public function getDateReport(): ?\DateTimeImmutable
    {
        return $this->dateReport;
    }

    public function setDateReport(\DateTimeImmutable $dateReport): static
    {
        $this->dateReport = $dateReport;

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
}
