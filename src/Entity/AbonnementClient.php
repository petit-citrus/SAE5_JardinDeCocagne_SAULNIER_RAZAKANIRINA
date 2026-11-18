<?php

namespace App\Entity;

use App\Repository\AbonnementClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AbonnementClientRepository::class)]
class AbonnementClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeImmutable $dateDebut = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeImmutable $dateFin = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column(length: 255)]
    private ?string $tarifApplique = null;

    #[ORM\ManyToOne(inversedBy: 'abonnementsClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'abonnementsClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Saison $saison = null;

    #[ORM\ManyToOne(inversedBy: 'abonnementsClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FormuleAbonnement $formuleAbonnement = null;

    #[ORM\ManyToOne(inversedBy: 'abonnementsClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TourneePointDepot $tourneePointDepot = null;

    #[ORM\ManyToOne(inversedBy: 'abonnementsClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CalendrierDiffusion $calendrierDiffusion = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'abonnementClient')]
    private Collection $commandes;

    /**
     * @var Collection<int, LivraisonUnitaire>
     */
    #[ORM\OneToMany(targetEntity: LivraisonUnitaire::class, mappedBy: 'abonnementClient')]
    private Collection $livraisonsUnitaires;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
        $this->livraisonsUnitaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeImmutable $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeImmutable
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeImmutable $dateFin): static
    {
        $this->dateFin = $dateFin;

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

    public function getTarifApplique(): ?string
    {
        return $this->tarifApplique;
    }

    public function setTarifApplique(string $tarifApplique): static
    {
        $this->tarifApplique = $tarifApplique;

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

    public function getSaison(): ?Saison
    {
        return $this->saison;
    }

    public function setSaison(?Saison $saison): static
    {
        $this->saison = $saison;

        return $this;
    }

    public function getFormuleAbonnement(): ?FormuleAbonnement
    {
        return $this->formuleAbonnement;
    }

    public function setFormuleAbonnement(?FormuleAbonnement $formuleAbonnement): static
    {
        $this->formuleAbonnement = $formuleAbonnement;

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

    public function getCalendrierDiffusion(): ?CalendrierDiffusion
    {
        return $this->calendrierDiffusion;
    }

    public function setCalendrierDiffusion(?CalendrierDiffusion $calendrierDiffusion): static
    {
        $this->calendrierDiffusion = $calendrierDiffusion;

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
            $commande->setAbonnementClient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            if ($commande->getAbonnementClient() === $this) {
                $commande->setAbonnementClient(null);
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
            $livraison->setAbonnementClient($this);
        }

        return $this;
    }

    public function removeLivraisonUnitaire(LivraisonUnitaire $livraison): static
    {
        if ($this->livraisonsUnitaires->removeElement($livraison)) {
            if ($livraison->getAbonnementClient() === $this) {
                $livraison->setAbonnementClient(null);
            }
        }

        return $this;
    }
}
