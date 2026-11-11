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

    public function verifierStocks(array $stocks, array $besoins): array
    {
        $resultats = [];

        foreach ($besoins as $legume => $quantiteNecessaire) {
            $stockDisponible = $stocks[$legume] ?? 0;

            $resultats[$legume] = [
                'suffisant' => $quantiteNecessaire <= $stockDisponible,
                'manquant' => max(0, $quantiteNecessaire - $stockDisponible),
                'restant' => max(0, $stockDisponible - $quantiteNecessaire),
            ];
        }

        return $resultats;
    }


        public function genererComposition(array $stocks, array $typesPaniers): array
        {
            $stockRestant = $stocks;
            $compositions = [];

            foreach ($typesPaniers as $type) {
                $nom = $type['nom'];
                $nombre = $type['nombre'];
                $valeurCible = $type['valeurCible'];

                $composition = [];
                $valeurActuelle = 0.0;

                if ($nombre <= 0) {
                    continue;
                }

                foreach ($stockRestant as $legume => $infos) {
                    $prix = $infos['prix'];
                    $quantiteDisponible = $infos['quantite'];

                    if ($prix <= 0 || $quantiteDisponible <= 0) {
                        continue;
                    }

                    // Quantité utilisable par panier en tenant compte
                    // du nombre total de paniers de ce type.
                    $quantiteMaxParPanier = $quantiteDisponible / $nombre;

                    // Valeur qu'il reste à atteindre.
                    $valeurManquante = max(
                        0,
                        $valeurCible - $valeurActuelle
                    );

                    // Quantité permettant d'atteindre la valeur cible.
                    $quantiteSouhaitee = $valeurManquante / $prix;

                    // Ne jamais dépasser le stock disponible.
                    $quantiteParPanier = min(
                        $quantiteMaxParPanier,
                        $quantiteSouhaitee
                    );

                    if ($quantiteParPanier <= 0) {
                        continue;
                    }

                    $composition[$legume] = $quantiteParPanier;

                    $valeurActuelle += $quantiteParPanier * $prix;

                    // Déduire la consommation de tous les paniers du type.
                    $stockRestant[$legume]['quantite'] -=
                        $quantiteParPanier * $nombre;

                    if ($valeurActuelle >= $valeurCible - 0.000001) {
                        break;
                    }
                }

                $compositions[$nom] = [
                    'nombre' => $nombre,
                    'composition' => $composition,
                    'valeur' => round($valeurActuelle, 2),
                    'valeurCible' => $valeurCible,
                ];
            }

            return [
                'paniers' => $compositions,
                'stocksRestants' => $stockRestant,
            ];
        }


    public function calculerValeurStock(array $stocks): float
    {
        $valeurTotale = 0.0;

        foreach ($stocks as $legume => $infos) {
            $quantite = $infos['quantite'];
            $prix = $infos['prix'];

            $valeurTotale += $quantite * $prix;
        }

        return $valeurTotale;
    }


    public function calculerCoefficientRepartition(
        array $stocks,
        array $typesPaniers
    ): float {
        $valeurStock = $this->calculerValeurStock($stocks);
        $valeurDemandee = 0.0;

        foreach ($typesPaniers as $type) {
            $valeurDemandee +=
                $type['nombre'] * $type['valeurCible'];
        }

        if ($valeurDemandee <= 0) {
            return 0.0;
        }

        return min(1.0, $valeurStock / $valeurDemandee);
    }


    public function genererCompositionEquitable(
        array $stocks,
        array $typesPaniers
    ): array {
        $coefficient = $this->calculerCoefficientRepartition(
            $stocks,
            $typesPaniers
        );

        $typesAjustes = [];

        foreach ($typesPaniers as $type) {
            $typeAjuste = $type;

            // Réduire la valeur cible selon le stock disponible.
            $typeAjuste['valeurCible'] =
                $type['valeurCible'] * $coefficient;

            $typesAjustes[] = $typeAjuste;
        }

        // Réutiliser notre premier algorithme.
        $resultat = $this->genererComposition(
            $stocks,
            $typesAjustes
        );

        // Conserver la vraie valeur cible de chaque panier.
        foreach ($typesPaniers as $type) {
            if ($type['nombre'] <= 0) {
                continue;
            }

            $nom = $type['nom'];

            $resultat['paniers'][$nom]['valeurCibleAjustee'] =
                $type['valeurCible'] * $coefficient;

            $resultat['paniers'][$nom]['valeurCible'] =
                $type['valeurCible'];
        }

        $resultat['coefficientRepartition'] = $coefficient;

        return $resultat;
    }

    public function analyserDiversite(
        array $resultat,
        int $minimumLegumes = 2
    ): array {
        if ($minimumLegumes < 1) {
            throw new \InvalidArgumentException(
                'Le minimum de légumes doit être positif.'
            );
        }

        $analyse = [];

        foreach ($resultat['paniers'] as $nom => $panier) {
            // Ignorer les types pour lesquels aucun panier
            // n'est demandé.
            if ($panier['nombre'] <= 0) {
                continue;
            }

            $nombreLegumes = 0;

            foreach ($panier['composition'] as $quantite) {
                if ($quantite > 0) {
                    $nombreLegumes++;
                }
            }

            $analyse[$nom] = [
                'nombreLegumes' => $nombreLegumes,
                'minimumLegumes' => $minimumLegumes,
                'diversiteSuffisante' =>
                    $nombreLegumes >= $minimumLegumes,
            ];
        }

        return $analyse;
    }






}
