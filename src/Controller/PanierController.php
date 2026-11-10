<?php

namespace App\Controller;

use App\Service\Panier\CompositionPanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/paniers', name: 'app_paniers')]
    public function index(
        CompositionPanierService $compositionService
    ): Response {
        // Données fictives pour la démonstration
        $stocks = [
            'carottes' => [
                'quantite' => 10.0,
                'prix' => 2.0,
            ],
            'pommes_de_terre' => [
                'quantite' => 15.0,
                'prix' => 1.5,
            ],
            'poireaux' => [
                'quantite' => 8.0,
                'prix' => 3.0,
            ],
        ];

        $typesPaniers = [
            [
                'nom' => 'Simple',
                'nombre' => 10,
                'valeurCible' => 5.0,
            ],
            [
                'nom' => 'Familial',
                'nombre' => 5,
                'valeurCible' => 10.0,
            ],
        ];

        // Appeler l'algorithme existant
        $resultat = $compositionService->genererCompositionEquitable(
            $stocks,
            $typesPaniers
        );

        return $this->render('paniers/index.html.twig', [
            'stocks' => $stocks,
            'resultat' => $resultat,
        ]);
    }
}
