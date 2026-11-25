<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $dateCommande = null;

    #[ORM\Column(type: 'float')]
    private ?float $totalTtc = null;

    #[ORM\Column(type: 'float')]
    private ?float $fraisLivraison = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Structure $structure = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?AbonnementClient $abonnementClient = null;

    /**
     * @var Collection<int, LivraisonUnitaire>
     */
    #[ORM\OneToMany(targetEntity: LivraisonUnitaire::class, mappedBy: 'commande')]
    private Collection $livraisonsUnitaires;

    /**
     * @var Collection<int, Facture>
     */
    #[ORM\OneToMany(targetEntity: Facture::class, mappedBy: 'commande')]
    private Collection $factures;

    public function __construct()
    {
        $this->livraisonsUnitaires = new ArrayCollection();
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCommande(): ?\DateTimeImmutable
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeImmutable $dateCommande): static
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->totalTtc;
    }

    public function setTotalTtc(float $totalTtc): static
    {
        $this->totalTtc = $totalTtc;

        return $this;
    }

    public function getFraisLivraison(): ?float
    {
        return $this->fraisLivraison;
    }

    public function setFraisLivraison(float $fraisLivraison): static
    {
        $this->fraisLivraison = $fraisLivraison;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

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

    public function getAbonnementClient(): ?AbonnementClient
    {
        return $this->abonnementClient;
    }

    public function setAbonnementClient(?AbonnementClient $abonnementClient): static
    {
        $this->abonnementClient = $abonnementClient;

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
            $livraison->setCommande($this);
        }

        return $this;
    }

    public function removeLivraisonUnitaire(LivraisonUnitaire $livraison): static
    {
        if ($this->livraisonsUnitaires->removeElement($livraison)) {
            if ($livraison->getCommande() === $this) {
                $livraison->setCommande(null);
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
            $facture->setCommande($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            if ($facture->getCommande() === $this) {
                $facture->setCommande(null);
            }
        }

        return $this;
    }
}
