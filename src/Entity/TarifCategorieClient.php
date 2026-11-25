<?php

namespace App\Entity;

use App\Repository\TarifCategorieClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarifCategorieClientRepository::class)]
class TarifCategorieClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    private ?float $prixSpecifique = null;

    #[ORM\ManyToOne(inversedBy: 'tarifsCategorieClient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'tarifsCategorieClient')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CategorieClient $categorieClient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixSpecifique(): ?float
    {
        return $this->prixSpecifique;
    }

    public function setPrixSpecifique(float $prixSpecifique): static
    {
        $this->prixSpecifique = $prixSpecifique;

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

    public function getCategorieClient(): ?CategorieClient
    {
        return $this->categorieClient;
    }

    public function setCategorieClient(?CategorieClient $categorieClient): static
    {
        $this->categorieClient = $categorieClient;

        return $this;
    }
}
