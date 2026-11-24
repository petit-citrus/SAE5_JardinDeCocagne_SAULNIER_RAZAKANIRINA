<?php

namespace App\Entity;

use App\Repository\TourneePointDepotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, AbonnementClient>
     */
    #[ORM\OneToMany(targetEntity: AbonnementClient::class, mappedBy: 'tourneePointDepot')]
    private Collection $abonnementsClients;

    /**
     * @var Collection<int, LivraisonUnitaire>
     */
    #[ORM\OneToMany(targetEntity: LivraisonUnitaire::class, mappedBy: 'tourneePointDepot')]
    private Collection $livraisonsUnitaires;

    public function __construct()
    {
        $this->abonnementsClients = new ArrayCollection();
        $this->livraisonsUnitaires = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, AbonnementClient>
     */
    public function getAbonnementsClients(): Collection
    {
        return $this->abonnementsClients;
    }

    public function addAbonnementClient(AbonnementClient $abonnement): static
    {
        if (!$this->abonnementsClients->contains($abonnement)) {
            $this->abonnementsClients->add($abonnement);
            $abonnement->setTourneePointDepot($this);
        }

        return $this;
    }

    public function removeAbonnementClient(AbonnementClient $abonnement): static
    {
        if ($this->abonnementsClients->removeElement($abonnement)) {
            if ($abonnement->getTourneePointDepot() === $this) {
                $abonnement->setTourneePointDepot(null);
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
            $livraison->setTourneePointDepot($this);
        }

        return $this;
    }

    public function removeLivraisonUnitaire(LivraisonUnitaire $livraison): static
    {
        if ($this->livraisonsUnitaires->removeElement($livraison)) {
            if ($livraison->getTourneePointDepot() === $this) {
                $livraison->setTourneePointDepot(null);
            }
        }

        return $this;
    }
}
