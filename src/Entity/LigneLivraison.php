<?php

namespace App\Entity;

use App\Repository\LigneLivraisonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneLivraisonRepository::class)]
class LigneLivraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    private ?float $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'lignesLivraison')]
    #[ORM\JoinColumn(nullable: false)]
    private ?LivraisonUnitaire $livraisonUnitaire = null;

    #[ORM\ManyToOne(inversedBy: 'lignesLivraison')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getLivraisonUnitaire(): ?LivraisonUnitaire
    {
        return $this->livraisonUnitaire;
    }

    public function setLivraisonUnitaire(?LivraisonUnitaire $livraisonUnitaire): static
    {
        $this->livraisonUnitaire = $livraisonUnitaire;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
