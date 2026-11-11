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

        $unites = [
            'carottes' => 'kg',
            'pommes_de_terre' => 'kg',
            'poireaux' => 'kg',
        ];

        $pasDistribution = [
            'carottes' => 0.1,
            'pommes_de_terre' => 0.25,
            'poireaux' => 0.1,
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

        // 3. Méthode de répartition sélectionnée par défaut.
        $methodeRepartition = 'equitable';

        $methodesAutorisees = [
            'equitable',
            'homogene',
        ];

        // 4. Initialisation des résultats et des erreurs.
        $erreurs = [];
        $resultat = null;
        $analyseDiversite = [];

        // 5. Traitement des données envoyées par le formulaire.
        if ($request->isMethod('POST')) {

            // Vérification du jeton CSRF.
            $token = $request->request->get('_csrf_token', '');

            if (
                !is_string($token)
                || !$this->isCsrfTokenValid(
                    'composition_paniers',
                    $token
                )
            ) {
                $erreurs[] =
                    'Jeton de sécurité invalide. Veuillez réessayer.';
            }

            // Récupération des données du formulaire.
            $quantitesEnvoyees = $request->request->all('stocks');
            $paniersEnvoyes = $request->request->all('paniers');

            // Récupération de la méthode de répartition.
            $methodeEnvoyee = $request->request->get(
                'methode_repartition',
                ''
            );

            if (
                !is_string($methodeEnvoyee)
                || !in_array(
                    $methodeEnvoyee,
                    $methodesAutorisees,
                    true
                )
            ) {
                $erreurs[] = 'Méthode de répartition invalide.';
            } else {
                $methodeRepartition = $methodeEnvoyee;
            }

            // Mise à jour des quantités de légumes.
            foreach ($quantites as $legume => $valeurParDefaut) {
                $saisie = $quantitesEnvoyees[$legume] ?? '';

                $quantites[$legume] = is_scalar($saisie)
                    ? (string) $saisie
                    : '';
            }

            // Mise à jour du nombre de paniers.
            foreach ($nombresPaniers as $nom => $valeurParDefaut) {
                $saisie = $paniersEnvoyes[$nom] ?? '';

                $nombresPaniers[$nom] = is_scalar($saisie)
                    ? (string) $saisie
                    : '';
            }
        }

        // 6. Validation et construction du tableau des stocks.
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
                $erreurs[] =
                    'Quantité invalide pour : '
                    . str_replace('_', ' ', $legume);

                continue;
            }

            $stocks[$legume] = [
                'quantite' => $quantite,
                'prix' => $prix[$legume],
                'unite' => $unites[$legume],
                'pasDistribution' => $pasDistribution[$legume] ?? null,
            ];
        }

        // 7. Validation et construction des types de paniers.
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
                $erreurs[] =
                    'Nombre de paniers invalide : ' . $nom;

                continue;
            }

            $typesPaniers[] = [
                'nom' => $nom,
                'nombre' => $nombre,
                'valeurCible' => $valeursCibles[$nom],
            ];
        }

        // 8. Génération de la composition selon la méthode choisie.
        if (empty($erreurs)) {

            if ($methodeRepartition === 'homogene') {

                // Nouvelle méthode : répartition des mêmes légumes
                // entre les différents types de paniers.
                $resultat = $compositionService
                    ->genererCompositionHomogene(
                        $stocks,
                        $typesPaniers
                    );

            } else {

                // Méthode précédente : répartition équitable
                // selon les valeurs cibles.
                $resultat = $compositionService
                    ->genererCompositionEquitable(
                        $stocks,
                        $typesPaniers
                    );
            }

            // 9. Analyse de la diversité des compositions.
            $analyseDiversite = $compositionService
                ->analyserDiversite($resultat);
        }

        // 10. Envoi des données et des résultats au template Twig.
        return $this->render('paniers/index.html.twig', [
            'prix' => $prix,
            'quantites' => $quantites,
            'nombresPaniers' => $nombresPaniers,
            'valeursCibles' => $valeursCibles,
            'methodeRepartition' => $methodeRepartition,
            'erreurs' => $erreurs,
            'resultat' => $resultat,
            'analyseDiversite' => $analyseDiversite,
            'unites' => $unites,
        ]);
    }
}
