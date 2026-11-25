<?php

namespace App\Entity;

use App\Repository\StructureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StructureRepository::class)]
class Structure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $siret = null;

    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\Column]
    private ?bool $assujettiTva = null;

    /**
     * @var Collection<int, CategorieClient>
     */
    #[ORM\OneToMany(targetEntity: CategorieClient::class, mappedBy: 'structure')]
    private Collection $categorieClients;

    /**
     * @var Collection<int, Utilisateur>
     */
    #[ORM\OneToMany(targetEntity: Utilisateur::class, mappedBy: 'structure')]
    private Collection $utilisateurs;

    /**
     * @var Collection<int, TypeAdhesion>
     */
    #[ORM\OneToMany(targetEntity: TypeAdhesion::class, mappedBy: 'structure')]
    private Collection $typesAdhesion;

    /**
     * @var Collection<int, Saison>
     */
    #[ORM\OneToMany(targetEntity: Saison::class, mappedBy: 'structure')]
    private Collection $saisons;

    /**
     * @var Collection<int, JourPreparation>
     */
    #[ORM\OneToMany(targetEntity: JourPreparation::class, mappedBy: 'structure')]
    private Collection $joursPreparation;

    /**
     * @var Collection<int, Tournee>
     */
    #[ORM\OneToMany(targetEntity: Tournee::class, mappedBy: 'structure')]
    private Collection $tournees;

    /**
     * @var Collection<int, PointDepot>
     */
    #[ORM\OneToMany(targetEntity: PointDepot::class, mappedBy: 'structure')]
    private Collection $pointsDepot;

    /**
     * @var Collection<int, Frequence>
     */
    #[ORM\OneToMany(targetEntity: Frequence::class, mappedBy: 'structure')]
    private Collection $frequences;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'structure')]
    private Collection $produits;

    /**
     * @var Collection<int, StockLegume>
     */
    #[ORM\OneToMany(targetEntity: StockLegume::class, mappedBy: 'structure')]
    private Collection $stocksLegumes;

    /**
     * @var Collection<int, FormuleAbonnement>
     */
    #[ORM\OneToMany(targetEntity: FormuleAbonnement::class, mappedBy: 'structure')]
    private Collection $formulesAbonnement;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'structure')]
    private Collection $commandes;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'structure')]
    private Collection $factures;

    /**
     * @var Collection<int, FeuilleDeChou>
     */
    #[ORM\OneToMany(targetEntity: FeuilleDeChou::class, mappedBy: 'structure')]
    private Collection $feuillesDeChou;

    /**
     * @var Collection<int, PasserelleIotSerre>
     */
    #[ORM\OneToMany(targetEntity: PasserelleIotSerre::class, mappedBy: 'structure')]
    private Collection $passerellesIotSerre;

    public function __construct()
    {
        $this->categorieClients = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
        $this->typesAdhesion = new ArrayCollection();
        $this->saisons = new ArrayCollection();
        $this->joursPreparation = new ArrayCollection();
        $this->tournees = new ArrayCollection();
        $this->pointsDepot = new ArrayCollection();
        $this->frequences = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->stocksLegumes = new ArrayCollection();
        $this->formulesAbonnement = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->factures = new ArrayCollection();
        $this->feuillesDeChou = new ArrayCollection();
        $this->passerellesIotSerre = new ArrayCollection();
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): static
    {
        $this->iban = $iban;

        return $this;
    }

    public function isAssujettiTva(): ?bool
    {
        return $this->assujettiTva;
    }

    public function setAssujettiTva(bool $assujettiTva): static
    {
        $this->assujettiTva = $assujettiTva;

        return $this;
    }

    /**
     * @return Collection<int, CategorieClient>
     */
    public function getCategorieClients(): Collection
    {
        return $this->categorieClients;
    }

    public function addCategorieClient(CategorieClient $categorieClient): static
    {
        if (!$this->categorieClients->contains($categorieClient)) {
            $this->categorieClients->add($categorieClient);
            $categorieClient->setStructure($this);
        }

        return $this;
    }

    public function removeCategorieClient(CategorieClient $categorieClient): static
    {
        if ($this->categorieClients->removeElement($categorieClient)) {
            if ($categorieClient->getStructure() === $this) {
                $categorieClient->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->setStructure($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            if ($utilisateur->getStructure() === $this) {
                $utilisateur->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TypeAdhesion>
     */
    public function getTypesAdhesion(): Collection
    {
        return $this->typesAdhesion;
    }

    public function addTypeAdhesion(TypeAdhesion $typeAdhesion): static
    {
        if (!$this->typesAdhesion->contains($typeAdhesion)) {
            $this->typesAdhesion->add($typeAdhesion);
            $typeAdhesion->setStructure($this);
        }

        return $this;
    }

    public function removeTypeAdhesion(TypeAdhesion $typeAdhesion): static
    {
        if ($this->typesAdhesion->removeElement($typeAdhesion)) {
            if ($typeAdhesion->getStructure() === $this) {
                $typeAdhesion->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Saison>
     */
    public function getSaisons(): Collection
    {
        return $this->saisons;
    }

    public function addSaison(Saison $saison): static
    {
        if (!$this->saisons->contains($saison)) {
            $this->saisons->add($saison);
            $saison->setStructure($this);
        }

        return $this;
    }

    public function removeSaison(Saison $saison): static
    {
        if ($this->saisons->removeElement($saison)) {
            if ($saison->getStructure() === $this) {
                $saison->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, JourPreparation>
     */
    public function getJoursPreparation(): Collection
    {
        return $this->joursPreparation;
    }

    public function addJourPreparation(JourPreparation $jourPreparation): static
    {
        if (!$this->joursPreparation->contains($jourPreparation)) {
            $this->joursPreparation->add($jourPreparation);
            $jourPreparation->setStructure($this);
        }

        return $this;
    }

    public function removeJourPreparation(JourPreparation $jourPreparation): static
    {
        if ($this->joursPreparation->removeElement($jourPreparation)) {
            if ($jourPreparation->getStructure() === $this) {
                $jourPreparation->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tournee>
     */
    public function getTournees(): Collection
    {
        return $this->tournees;
    }

    public function addTournee(Tournee $tournee): static
    {
        if (!$this->tournees->contains($tournee)) {
            $this->tournees->add($tournee);
            $tournee->setStructure($this);
        }

        return $this;
    }

    public function removeTournee(Tournee $tournee): static
    {
        if ($this->tournees->removeElement($tournee)) {
            if ($tournee->getStructure() === $this) {
                $tournee->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PointDepot>
     */
    public function getPointsDepot(): Collection
    {
        return $this->pointsDepot;
    }

    public function addPointDepot(PointDepot $pointDepot): static
    {
        if (!$this->pointsDepot->contains($pointDepot)) {
            $this->pointsDepot->add($pointDepot);
            $pointDepot->setStructure($this);
        }

        return $this;
    }

    public function removePointDepot(PointDepot $pointDepot): static
    {
        if ($this->pointsDepot->removeElement($pointDepot)) {
            if ($pointDepot->getStructure() === $this) {
                $pointDepot->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Frequence>
     */
    public function getFrequences(): Collection
    {
        return $this->frequences;
    }

    public function addFrequence(Frequence $frequence): static
    {
        if (!$this->frequences->contains($frequence)) {
            $this->frequences->add($frequence);
            $frequence->setStructure($this);
        }

        return $this;
    }

    public function removeFrequence(Frequence $frequence): static
    {
        if ($this->frequences->removeElement($frequence)) {
            if ($frequence->getStructure() === $this) {
                $frequence->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setStructure($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            if ($produit->getStructure() === $this) {
                $produit->setStructure(null);
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
            $stockLegume->setStructure($this);
        }

        return $this;
    }

    public function removeStockLegume(StockLegume $stockLegume): static
    {
        if ($this->stocksLegumes->removeElement($stockLegume)) {
            if ($stockLegume->getStructure() === $this) {
                $stockLegume->setStructure(null);
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
            $formule->setStructure($this);
        }

        return $this;
    }

    public function removeFormuleAbonnement(FormuleAbonnement $formule): static
    {
        if ($this->formulesAbonnement->removeElement($formule)) {
            if ($formule->getStructure() === $this) {
                $formule->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setStructure($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            if ($commande->getStructure() === $this) {
                $commande->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setStructure($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            if ($facture->getStructure() === $this) {
                $facture->setStructure(null);
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
            $feuille->setStructure($this);
        }

        return $this;
    }

    public function removeFeuilleDeChou(FeuilleDeChou $feuille): static
    {
        if ($this->feuillesDeChou->removeElement($feuille)) {
            if ($feuille->getStructure() === $this) {
                $feuille->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PasserelleIotSerre>
     */
    public function getPasserellesIotSerre(): Collection
    {
        return $this->passerellesIotSerre;
    }

    public function addPasserelleIotSerre(PasserelleIotSerre $passerelle): static
    {
        if (!$this->passerellesIotSerre->contains($passerelle)) {
            $this->passerellesIotSerre->add($passerelle);
            $passerelle->setStructure($this);
        }

        return $this;
    }

    public function removePasserelleIotSerre(PasserelleIotSerre $passerelle): static
    {
        if ($this->passerellesIotSerre->removeElement($passerelle)) {
            if ($passerelle->getStructure() === $this) {
                $passerelle->setStructure(null);
            }
        }

        return $this;
    }
}
