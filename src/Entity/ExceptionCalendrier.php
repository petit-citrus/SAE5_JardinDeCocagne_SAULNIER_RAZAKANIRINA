<?php

namespace App\Entity;

use App\Repository\ExceptionCalendrierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExceptionCalendrierRepository::class)]
class ExceptionCalendrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeImmutable $dateOriginale = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeImmutable $dateModifiee = null;

    #[ORM\ManyToOne(inversedBy: 'exceptionsCalendrier')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CalendrierDiffusion $calendrierDiffusion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOriginale(): ?\DateTimeImmutable
    {
        return $this->dateOriginale;
    }

    public function setDateOriginale(\DateTimeImmutable $dateOriginale): static
    {
        $this->dateOriginale = $dateOriginale;

        return $this;
    }

    public function getDateModifiee(): ?\DateTimeImmutable
    {
        return $this->dateModifiee;
    }

    public function setDateModifiee(\DateTimeImmutable $dateModifiee): static
    {
        $this->dateModifiee = $dateModifiee;

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
}
