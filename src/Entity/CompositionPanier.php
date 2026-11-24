<?php

namespace App\Entity;

use App\Repository\CompositionPanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompositionPanierRepository::class)]
class CompositionPanier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'json')]
    private array $contenu = [];

    #[ORM\Column(type: 'float')]
    private ?float $coutTotal = null;

    #[ORM\Column]
    private ?bool $valide = null;

    #[ORM\ManyToOne(inversedBy: 'compositionsPaniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'compositionsPaniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Saison $saison = null;

    #[ORM\ManyToOne(inversedBy: 'compositionsPaniers')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Tournee $tournee = null;

    /**
     * @var Collection<int, LigneCompositionPanier>
     */
    #[ORM\OneToMany(targetEntity: LigneCompositionPanier::class, mappedBy: 'compositionPanier')]
    private Collection $lignesCompositionPanier;

    /**
     * @var Collection<int, LivraisonUnitaire>
     */
    #[ORM\OneToMany(targetEntity: LivraisonUnitaire::class, mappedBy: 'compositionPanier')]
    private Collection $livraisonsUnitaires;

    public function __construct()
    {
        $this->lignesCompositionPanier = new ArrayCollection();
        $this->livraisonsUnitaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): array
    {
        return $this->contenu;
    }

    public function setContenu(array $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCoutTotal(): ?float
    {
        return $this->coutTotal;
    }

    public function setCoutTotal(float $coutTotal): static
    {
        $this->coutTotal = $coutTotal;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): static
    {
        $this->valide = $valide;

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

    /**
     * @return Collection<int, LigneCompositionPanier>
     */
    public function getLignesCompositionPanier(): Collection
    {
        return $this->lignesCompositionPanier;
    }

    public function addLigneCompositionPanier(LigneCompositionPanier $ligne): static
    {
        if (!$this->lignesCompositionPanier->contains($ligne)) {
            $this->lignesCompositionPanier->add($ligne);
            $ligne->setCompositionPanier($this);
        }

        return $this;
    }

    public function removeLigneCompositionPanier(LigneCompositionPanier $ligne): static
    {
        if ($this->lignesCompositionPanier->removeElement($ligne)) {
            if ($ligne->getCompositionPanier() === $this) {
                $ligne->setCompositionPanier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LivraisonUnitaire>
     */
    public function getLivraisonsUnitaires(): Collection
    {
        return $this->livraisonsUnitaires;
    }

    public function addLivraisonUnitaire(LivraisonUnitaire $livraison): static
    {
        if (!$this->livraisonsUnitaires->contains($livraison)) {
            $this->livraisonsUnitaires->add($livraison);
            $livraison->setCompositionPanier($this);
        }

        return $this;
    }

    public function removeLivraisonUnitaire(LivraisonUnitaire $livraison): static
    {
        if ($this->livraisonsUnitaires->removeElement($livraison)) {
            if ($livraison->getCompositionPanier() === $this) {
                $livraison->setCompositionPanier(null);
            }
        }

        return $this;
    }
}
