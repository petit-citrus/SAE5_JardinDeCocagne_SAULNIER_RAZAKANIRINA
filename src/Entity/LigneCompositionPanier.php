<?php

namespace App\Entity;

use App\Repository\LigneCompositionPanierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneCompositionPanierRepository::class)]
class LigneCompositionPanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    private ?float $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'lignesCompositionPanier')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CompositionPanier $compositionPanier = null;

    #[ORM\ManyToOne(inversedBy: 'lignesCompositionPanier')]
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

    public function getCompositionPanier(): ?CompositionPanier
    {
        return $this->compositionPanier;
    }

    public function setCompositionPanier(?CompositionPanier $compositionPanier): static
    {
        $this->compositionPanier = $compositionPanier;

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
