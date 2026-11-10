<?php

namespace App\Service\Panier;

class CompositionPanierService
{
    public function calculerValeur(array $legumes): float
    {
        $total = 0;

        foreach ($legumes as $legume) {
            $total += $legume['quantite'] * $legume['prixUnitaire'];
        }

        return round($total, 2);
    }

    public function stockSuffisant(
        float $stockDisponible,
        float $quantiteParPanier,
        int $nombrePaniers
    ): bool {
        $quantiteNecessaire = $quantiteParPanier * $nombrePaniers;

        return $quantiteNecessaire <= $stockDisponible;
    }


    public function calculerBesoinTotal(array $paniers): float
    {
        $total = 0;

        foreach ($paniers as $panier) {
            $total += $panier['nombre'] * $panier['quantiteParPanier'];
        }

        return $total;
    }


}
