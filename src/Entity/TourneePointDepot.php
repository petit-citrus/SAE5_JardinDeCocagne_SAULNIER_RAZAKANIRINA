<?php

namespace App\Entity;

use App\Repository\TourneePointDepotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TourneePointDepotRepository::class)]
class TourneePointDepot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $ordreLivraison = null;

    #[ORM\Column(length: 255)]
    private ?string $creneauInterne = null;

    #[ORM\Column(length: 255)]
    private ?string $creneauClient = null;

    #[ORM\ManyToOne(inversedBy: 'tourneePointsDepot')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tournee $tournee = null;

    #[ORM\ManyToOne(inversedBy: 'tourneePointsDepot')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PointDepot $pointDepot = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdreLivraison(): ?int
    {
        return $this->ordreLivraison;
    }

    public function setOrdreLivraison(int $ordreLivraison): static
    {
        $this->ordreLivraison = $ordreLivraison;

        return $this;
    }

    public function getCreneauInterne(): ?string
    {
        return $this->creneauInterne;
    }

    public function setCreneauInterne(string $creneauInterne): static
    {
        $this->creneauInterne = $creneauInterne;

        return $this;
    }

    public function getCreneauClient(): ?string
    {
        return $this->creneauClient;
    }

    public function setCreneauClient(string $creneauClient): static
    {
        $this->creneauClient = $creneauClient;

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

    public function getPointDepot(): ?PointDepot
    {
        return $this->pointDepot;
    }

    public function setPointDepot(?PointDepot $pointDepot): static
    {
        $this->pointDepot = $pointDepot;

        return $this;
    }
}
