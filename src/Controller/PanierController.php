<?php

namespace App\Controller;

use App\Service\Panier\CompositionPanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/paniers', name: 'app_paniers', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        CompositionPanierService $compositionService
    ): Response {
        // 1. Prix fixes pour cette première version.
        $prix = [
            'carottes' => 2.0,
            'pommes_de_terre' => 1.5,
            'poireaux' => 3.0,
        ];

        // 2. Valeurs initiales affichées dans le formulaire.
        $quantites = [
            'carottes' => '10',
            'pommes_de_terre' => '15',
            'poireaux' => '8',
        ];

        $nombresPaniers = [
            'Simple' => '10',
            'Familial' => '5',
        ];

        $valeursCibles = [
            'Simple' => 5.0,
            'Familial' => 10.0,
        ];

        $erreurs = [];
        $resultat = null;
        $analyseDiversite = [];

        if ($request->isMethod('POST')) {
    $token = $request->request->get('_csrf_token', '');

        if (
            !is_string($token)
            || !$this->isCsrfTokenValid('composition_paniers', $token)
        ) {
            $erreurs[] = 'Jeton de sécurité invalide. Veuillez réessayer.';
        }
    }

        // 3. Récupération des données envoyées par le formulaire.
        if ($request->isMethod('POST')) {
            $quantitesEnvoyees = $request->request->all('stocks');
            $paniersEnvoyes = $request->request->all('paniers');

            // Mettre à jour les quantités de légumes.
            foreach ($quantites as $legume => $valeurParDefaut) {
                $saisie = $quantitesEnvoyees[$legume] ?? '';

                $quantites[$legume] = is_scalar($saisie)
                    ? (string) $saisie
                    : '';
            }

            // Mettre à jour les nombres de paniers.
            foreach ($nombresPaniers as $nom => $valeurParDefaut) {
                $saisie = $paniersEnvoyes[$nom] ?? '';

                $nombresPaniers[$nom] = is_scalar($saisie)
                    ? (string) $saisie
                    : '';
            }
        }

    //     dd([
    // 'methode' => $request->getMethod(),
    // 'quantites_apres_POST' => $quantites,
    // 'nombres_paniers' => $nombresPaniers,
    //         ]);

        // 4. Validation et construction des stocks.
        $stocks = [];

        foreach ($quantites as $legume => $saisie) {
            $quantite = filter_var(
                $saisie,
                FILTER_VALIDATE_FLOAT
            );

            if (
                $quantite === false
                || !is_finite($quantite)
                || $quantite < 0
                || $quantite > 1000000
            ) {
                $erreurs[] = "Quantité invalide pour : "
                    . str_replace('_', ' ', $legume);

                continue;
            }

            $stocks[$legume] = [
                'quantite' => $quantite,
                'prix' => $prix[$legume],
            ];
        }

        // 5. Validation et construction des types de paniers.
        $typesPaniers = [];

        foreach ($nombresPaniers as $nom => $saisie) {
            $nombre = filter_var(
                $saisie,
                FILTER_VALIDATE_INT
            );

            if (
                $nombre === false
                || $nombre < 0
                || $nombre > 100000
            ) {
                $erreurs[] = "Nombre de paniers invalide : " . $nom;

                continue;
            }

            $typesPaniers[] = [
                'nom' => $nom,
                'nombre' => $nombre,
                'valeurCible' => $valeursCibles[$nom],
            ];
        }

        // 6. Génération des compositions si les données sont valides.
        
        if (empty($erreurs)) {
            $resultat = $compositionService
                ->genererCompositionEquitable(
                    $stocks,
                    $typesPaniers
                );

            $analyseDiversite = $compositionService
                ->analyserDiversite($resultat);
        }

        // 7. Affichage des données et des résultats dans Twig.
        return $this->render('paniers/index.html.twig', [
            'prix' => $prix,
            'quantites' => $quantites,
            'nombresPaniers' => $nombresPaniers,
            'valeursCibles' => $valeursCibles,
            'erreurs' => $erreurs,
            'resultat' => $resultat,
            'analyseDiversite' => $analyseDiversite,
        ]);
    }
}
