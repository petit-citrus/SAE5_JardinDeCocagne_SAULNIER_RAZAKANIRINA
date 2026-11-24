<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $unite = null;

    #[ORM\Column(type: 'float')]
    private ?float $prixHt = null;

    #[ORM\Column(type: 'float')]
    private ?float $tva = null;

    #[ORM\Column(type: 'float')]
    private ?float $marge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    /**
     * @var Collection<int, TarifCategorieClient>
     */
    #[ORM\OneToMany(targetEntity: TarifCategorieClient::class, mappedBy: 'produit')]
    private Collection $tarifsCategorieClient;

    /**
     * @var Collection<int, StockLegume>
     */
    #[ORM\OneToMany(targetEntity: StockLegume::class, mappedBy: 'produit')]
    private Collection $stocksLegumes;

    /**
     * @var Collection<int, CompositionPanier>
     */
    #[ORM\OneToMany(targetEntity: CompositionPanier::class, mappedBy: 'produit')]
    private Collection $compositionsPaniers;

    /**
     * @var Collection<int, LigneCompositionPanier>
     */
    #[ORM\OneToMany(targetEntity: LigneCompositionPanier::class, mappedBy: 'produit')]
    private Collection $lignesCompositionPanier;

    /**
     * @var Collection<int, FormuleAbonnement>
     */
    #[ORM\OneToMany(targetEntity: FormuleAbonnement::class, mappedBy: 'produit')]
    private Collection $formulesAbonnement;

    /**
     * @var Collection<int, LigneLivraison>
     */
    #[ORM\OneToMany(targetEntity: LigneLivraison::class, mappedBy: 'produit')]
    private Collection $lignesLivraison;

    /**
     * @var Collection<int, FeuilleDeChou>
     */
    #[ORM\OneToMany(targetEntity: FeuilleDeChou::class, mappedBy: 'produit')]
    private Collection $feuillesDeChou;

    /**
     * @var Collection<int, Recette>
     */
    #[ORM\ManyToMany(targetEntity: Recette::class, mappedBy: 'produits')]
    private Collection $recettes;

    public function __construct()
    {
        $this->tarifsCategorieClient = new ArrayCollection();
        $this->stocksLegumes = new ArrayCollection();
        $this->compositionsPaniers = new ArrayCollection();
        $this->lignesCompositionPanier = new ArrayCollection();
        $this->formulesAbonnement = new ArrayCollection();
        $this->lignesLivraison = new ArrayCollection();
        $this->feuillesDeChou = new ArrayCollection();
        $this->recettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getPrixHt(): ?float
    {
        return $this->prixHt;
    }

    public function setPrixHt(float $prixHt): static
    {
        $this->prixHt = $prixHt;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getMarge(): ?float
    {
        return $this->marge;
    }

    public function setMarge(float $marge): static
    {
        $this->marge = $marge;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

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
     * @return Collection<int, TarifCategorieClient>
     */
    public function getTarifsCategorieClient(): Collection
    {
        return $this->tarifsCategorieClient;
    }

    public function addTarifCategorieClient(TarifCategorieClient $tarif): static
    {
        if (!$this->tarifsCategorieClient->contains($tarif)) {
            $this->tarifsCategorieClient->add($tarif);
            $tarif->setProduit($this);
        }

        return $this;
    }

    public function removeTarifCategorieClient(TarifCategorieClient $tarif): static
    {
        if ($this->tarifsCategorieClient->removeElement($tarif)) {
            if ($tarif->getProduit() === $this) {
                $tarif->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StockLegume>
     */
    public function getStocksLegumes(): Collection
    {
        return $this->stocksLegumes;
    }

    public function addStockLegume(StockLegume $stockLegume): static
    {
        if (!$this->stocksLegumes->contains($stockLegume)) {
            $this->stocksLegumes->add($stockLegume);
            $stockLegume->setProduit($this);
        }

        return $this;
    }

    public function removeStockLegume(StockLegume $stockLegume): static
    {
        if ($this->stocksLegumes->removeElement($stockLegume)) {
            if ($stockLegume->getProduit() === $this) {
                $stockLegume->setProduit(null);
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
            $compositionPanier->setProduit($this);
        }

        return $this;
    }

    public function removeCompositionPanier(CompositionPanier $compositionPanier): static
    {
        if ($this->compositionsPaniers->removeElement($compositionPanier)) {
            if ($compositionPanier->getProduit() === $this) {
                $compositionPanier->setProduit(null);
            }
        }

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
            $ligne->setProduit($this);
        }

        return $this;
    }

    public function removeLigneCompositionPanier(LigneCompositionPanier $ligne): static
    {
        if ($this->lignesCompositionPanier->removeElement($ligne)) {
            if ($ligne->getProduit() === $this) {
                $ligne->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FormuleAbonnement>
     */
    public function getFormulesAbonnement(): Collection
    {
        return $this->formulesAbonnement;
    }

    public function addFormuleAbonnement(FormuleAbonnement $formule): static
    {
        if (!$this->formulesAbonnement->contains($formule)) {
            $this->formulesAbonnement->add($formule);
            $formule->setProduit($this);
        }

        return $this;
    }

    public function removeFormuleAbonnement(FormuleAbonnement $formule): static
    {
        if ($this->formulesAbonnement->removeElement($formule)) {
            if ($formule->getProduit() === $this) {
                $formule->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LigneLivraison>
     */
    public function getLignesLivraison(): Collection
    {
        return $this->lignesLivraison;
    }

    public function addLigneLivraison(LigneLivraison $ligne): static
    {
        if (!$this->lignesLivraison->contains($ligne)) {
            $this->lignesLivraison->add($ligne);
            $ligne->setProduit($this);
        }

        return $this;
    }

    public function removeLigneLivraison(LigneLivraison $ligne): static
    {
        if ($this->lignesLivraison->removeElement($ligne)) {
            if ($ligne->getProduit() === $this) {
                $ligne->setProduit(null);
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
            $feuille->setProduit($this);
        }

        return $this;
    }

    public function removeFeuilleDeChou(FeuilleDeChou $feuille): static
    {
        if ($this->feuillesDeChou->removeElement($feuille)) {
            if ($feuille->getProduit() === $this) {
                $feuille->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recette>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->addProduit($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            $recette->removeProduit($this);
        }

        return $this;
    }
}
