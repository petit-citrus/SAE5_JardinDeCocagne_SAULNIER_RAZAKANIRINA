<?php

namespace App\Entity;

use App\Repository\LivraisonUnitaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonUnitaireRepository::class)]
class LivraisonUnitaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeImmutable $datePrevue = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeImmutable $dateEffective = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'livraisonsUnitaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'livraisonsUnitaires')]
    #[ORM\JoinColumn(nullable: true)]
    private ?AbonnementClient $abonnementClient = null;

    #[ORM\ManyToOne(inversedBy: 'livraisonsUnitaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TourneePointDepot $tourneePointDepot = null;

    #[ORM\ManyToOne(inversedBy: 'livraisonsUnitaires')]
    #[ORM\JoinColumn(nullable: true)]
    private ?CompositionPanier $compositionPanier = null;

    /**
     * @var Collection<int, LigneLivraison>
     */
    #[ORM\OneToMany(targetEntity: LigneLivraison::class, mappedBy: 'livraisonUnitaire')]
    private Collection $lignesLivraison;

    public function __construct()
    {
        $this->lignesLivraison = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePrevue(): ?\DateTimeImmutable
    {
        return $this->datePrevue;
    }

    public function setDatePrevue(\DateTimeImmutable $datePrevue): static
    {
        $this->datePrevue = $datePrevue;

        return $this;
    }

    public function getDateEffective(): ?\DateTimeImmutable
    {
        return $this->dateEffective;
    }

    public function setDateEffective(?\DateTimeImmutable $dateEffective): static
    {
        $this->dateEffective = $dateEffective;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

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

    public function getTourneePointDepot(): ?TourneePointDepot
    {
        return $this->tourneePointDepot;
    }

    public function setTourneePointDepot(?TourneePointDepot $tourneePointDepot): static
    {
        $this->tourneePointDepot = $tourneePointDepot;

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
            $ligne->setLivraisonUnitaire($this);
        }

        return $this;
    }

    public function removeLigneLivraison(LigneLivraison $ligne): static
    {
        if ($this->lignesLivraison->removeElement($ligne)) {
            if ($ligne->getLivraisonUnitaire() === $this) {
                $ligne->setLivraisonUnitaire(null);
            }
        }

        return $this;
    }
}
