<?php

namespace App\Tests\Service\Panier;

use PHPUnit\Framework\TestCase;
use App\Service\Panier\CompositionPanierService;

class CompositionPanierServiceTest extends TestCase
{
    public function testCalculerValeur(): void
    {
        // Créer notre service
        $service = new CompositionPanierService();

        // Préparer un panier fictif
        $legumes = [
            ['quantite' => 1, 'prixUnitaire' => 2.00],
            ['quantite' => 1, 'prixUnitaire' => 1.50],
            ['quantite' => 0.5, 'prixUnitaire' => 3.00],
        ];

        // Calculer la valeur du panier
        $resultat = $service->calculerValeur($legumes);

        // Vérifier que le résultat est correct
        $this->assertEquals(5.00, $resultat);
    }

    public function testStockSuffisant(): void
    {
        $service = new CompositionPanierService();

        // 10 paniers × 1 kg = 10 kg nécessaires
        // Nous avons 30 kg disponibles.
        $resultat = $service->stockSuffisant(30, 1, 10);

        $this->assertTrue($resultat);
    }

    public function testStockInsuffisant(): void
    {
        $service = new CompositionPanierService();

        // 40 paniers × 1 kg = 40 kg nécessaires
        // Nous avons seulement 30 kg.
        $resultat = $service->stockSuffisant(30, 1, 40);

        $this->assertFalse($resultat);
    }


    public function testCalculerBesoinTotal(): void
    {
        $service = new CompositionPanierService();

        $paniers = [
            ['nombre' => 10, 'quantiteParPanier' => 0.5],
            ['nombre' => 5, 'quantiteParPanier' => 1.0],
        ];

        $resultat = $service->calculerBesoinTotal($paniers);

        $this->assertEquals(10.0, $resultat);
    }

    
public function testVerifierStocks(): void
{
    $service = new CompositionPanierService();

    $stocks = [
        'carottes' => 30,
        'pommes_de_terre' => 40,
        'poireaux' => 8,
    ];

    $besoins = [
        'carottes' => 10,
        'pommes_de_terre' => 15,
        'poireaux' => 10,
    ];

    $resultats = $service->verifierStocks($stocks, $besoins);

    $this->assertTrue($resultats['carottes']['suffisant']);
    $this->assertEquals(20, $resultats['carottes']['restant']);

    $this->assertTrue($resultats['pommes_de_terre']['suffisant']);
    $this->assertEquals(25, $resultats['pommes_de_terre']['restant']);

    $this->assertFalse($resultats['poireaux']['suffisant']);
    $this->assertEquals(2, $resultats['poireaux']['manquant']);
}


    public function testGenererComposition(): void
    {
        $service = new CompositionPanierService();

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

        $resultat = $service->genererComposition(
            $stocks,
            $typesPaniers
        );
       

        // Vérifier que les deux types ont été générés.
        $this->assertArrayHasKey(
            'Simple',
            $resultat['paniers']
        );

        $this->assertArrayHasKey(
            'Familial',
            $resultat['paniers']
        );

        // Vérifier qu'aucun stock n'est devenu négatif.
        foreach ($resultat['stocksRestants'] as $stock) {
            $this->assertGreaterThanOrEqual(
                -0.000001,
                $stock['quantite']
            );
        }

        // Vérifier que les paniers ne dépassent pas
        // leur valeur cible.
        foreach ($resultat['paniers'] as $panier) {
            $this->assertLessThanOrEqual(
                $panier['valeurCible'] + 0.000001,
                $panier['valeur']
            );
        }
    }

    public function testCalculerValeurStock(): void
    {
        $service = new CompositionPanierService();

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

        $resultat = $service->calculerValeurStock($stocks);

        $this->assertEqualsWithDelta(66.50, $resultat, 0.001);
    }

    public function testCalculerCoefficientRepartition(): void
    {
        $service = new CompositionPanierService();

        $stocks = [
            'carottes' => ['quantite' => 10.0, 'prix' => 2.0],
            'pommes_de_terre' => ['quantite' => 15.0, 'prix' => 1.5],
            'poireaux' => ['quantite' => 8.0, 'prix' => 3.0],
        ];

        $typesPaniers = [
            ['nom' => 'Simple', 'nombre' => 10, 'valeurCible' => 5.0],
            ['nom' => 'Familial', 'nombre' => 5, 'valeurCible' => 10.0],
        ];

        $coefficient = $service->calculerCoefficientRepartition(
            $stocks,
            $typesPaniers
        );

        $this->assertEqualsWithDelta(0.665, $coefficient, 0.000001);
    }


    public function testGenererCompositionEquitable(): void
    {
        $service = new CompositionPanierService();

        $stocks = [
            'carottes' => ['quantite' => 10.0, 'prix' => 2.0],
            'pommes_de_terre' => ['quantite' => 15.0, 'prix' => 1.5],
            'poireaux' => ['quantite' => 8.0, 'prix' => 3.0],
        ];

        $typesPaniers = [
            ['nom' => 'Simple', 'nombre' => 10, 'valeurCible' => 5.0],
            ['nom' => 'Familial', 'nombre' => 5, 'valeurCible' => 10.0],
        ];

        $resultat = $service->genererCompositionEquitable(
            $stocks,
            $typesPaniers
        );

        $this->assertEqualsWithDelta(
            0.665,
            $resultat['coefficientRepartition'],
            0.000001
        );

        $simple = $resultat['paniers']['Simple'];
        $familial = $resultat['paniers']['Familial'];

        // Les deux types atteignent une proportion similaire
        // de leur valeur cible.
        $this->assertEqualsWithDelta(
            $simple['valeur'] / $simple['valeurCible'],
            $familial['valeur'] / $familial['valeurCible'],
            0.005
        );

        $this->assertEqualsWithDelta(3.325, $simple['valeur'], 0.01);
        $this->assertEqualsWithDelta(6.65, $familial['valeur'], 0.01);

        // Aucun stock négatif.
        foreach ($resultat['stocksRestants'] as $stock) {
            $this->assertGreaterThanOrEqual(
                -0.000001,
                $stock['quantite']
            );
        }
    }

    public function testCoefficientAvecStockSuffisant(): void
    {
        $service = new CompositionPanierService();

        $stocks = [
            'carottes' => [
                'quantite' => 100.0,
                'prix' => 2.0,
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

        $coefficient = $service->calculerCoefficientRepartition(
            $stocks,
            $typesPaniers
        );

        $this->assertEqualsWithDelta(
            1.0,
            $coefficient,
            0.000001
        );

        $resultat = $service->genererCompositionEquitable(
            $stocks,
            $typesPaniers
        );

        $this->assertEqualsWithDelta(
            5.0,
            $resultat['paniers']['Simple']['valeur'],
            0.01
        );

        $this->assertEqualsWithDelta(
            10.0,
            $resultat['paniers']['Familial']['valeur'],
            0.01
        );
    }


    public function testCompositionSansStock(): void
    {
        $service = new CompositionPanierService();

        $stocks = [];

        $typesPaniers = [
            [
                'nom' => 'Simple',
                'nombre' => 10,
                'valeurCible' => 5.0,
            ],
        ];

        $resultat = $service->genererCompositionEquitable(
            $stocks,
            $typesPaniers
        );

        $this->assertEqualsWithDelta(
            0.0,
            $resultat['coefficientRepartition'],
            0.000001
        );

        $this->assertEquals(
            [],
            $resultat['paniers']['Simple']['composition']
        );

        $this->assertEqualsWithDelta(
            0.0,
            $resultat['paniers']['Simple']['valeur'],
            0.001
        );
    }


public function testInfluenceOrdreDesPaniers(): void
{
    $service = new CompositionPanierService();

    $stocks = [
        'carottes' => ['quantite' => 10.0, 'prix' => 2.0],
        'pommes_de_terre' => ['quantite' => 15.0, 'prix' => 1.5],
        'poireaux' => ['quantite' => 8.0, 'prix' => 3.0],
    ];

    $typesPaniers = [
        ['nom' => 'Simple', 'nombre' => 10, 'valeurCible' => 5.0],
        ['nom' => 'Familial', 'nombre' => 5, 'valeurCible' => 10.0],
    ];

    $resultatNormal = $service->genererCompositionEquitable(
        $stocks,
        $typesPaniers
    );

    $resultatInverse = $service->genererCompositionEquitable(
        $stocks,
        array_reverse($typesPaniers)
    );

    // Comparer la valeur obtenue pour chaque type.
    $this->assertEqualsWithDelta(
        $resultatNormal['paniers']['Simple']['valeur'],
        $resultatInverse['paniers']['Simple']['valeur'],
        0.01
    );

    $this->assertEqualsWithDelta(
        $resultatNormal['paniers']['Familial']['valeur'],
        $resultatInverse['paniers']['Familial']['valeur'],
        0.01
    );
}


    public function testDiversiteInsuffisante(): void
    {
        $service = new CompositionPanierService();

        $resultat = [
            'paniers' => [
                'Simple' => [
                    'nombre' => 10,
                    'composition' => [
                        'carottes' => 2.5,
                    ],
                ],
            ],
        ];

        $analyse = $service->analyserDiversite($resultat);

        $this->assertEquals(
            1,
            $analyse['Simple']['nombreLegumes']
        );

        $this->assertFalse(
            $analyse['Simple']['diversiteSuffisante']
        );
    }

    public function testDiversiteSuffisante(): void
    {
        $service = new CompositionPanierService();

        $resultat = [
            'paniers' => [
                'Simple' => [
                    'nombre' => 10,
                    'composition' => [
                        'carottes' => 1.0,
                        'pommes_de_terre' => 1.5,
                        'poireaux' => 0.25,
                    ],
                ],
            ],
        ];

        $analyse = $service->analyserDiversite($resultat);

        $this->assertEquals(
            3,
            $analyse['Simple']['nombreLegumes']
        );

        $this->assertTrue(
            $analyse['Simple']['diversiteSuffisante']
        );
    }
    public function testCompositionHomogeneAvecStockInsuffisant(): void
    {
        $service = new CompositionPanierService();

        $stocks = [
            'carottes' => ['quantite' => 10.0, 'prix' => 2.0],
            'pommes_de_terre' => ['quantite' => 15.0, 'prix' => 1.5],
            'poireaux' => ['quantite' => 8.0, 'prix' => 3.0],
        ];

        $typesPaniers = [
            ['nom' => 'Simple', 'nombre' => 10, 'valeurCible' => 5.0],
            ['nom' => 'Familial', 'nombre' => 5, 'valeurCible' => 10.0],
        ];

        $resultat = $service->genererCompositionHomogene(
            $stocks,
            $typesPaniers
        );

        $simple = $resultat['paniers']['Simple'];
        $familial = $resultat['paniers']['Familial'];

        $this->assertCount(3, $simple['composition']);
        $this->assertCount(3, $familial['composition']);

        $this->assertEqualsWithDelta(
            0.5,
            $simple['composition']['carottes'],
            0.000001
        );

        $this->assertEqualsWithDelta(
            1.0,
            $familial['composition']['carottes'],
            0.000001
        );

        $this->assertEqualsWithDelta(
            3.33,
            $simple['valeur'],
            0.01
        );

        $this->assertEqualsWithDelta(
            6.65,
            $familial['valeur'],
            0.01
        );

        foreach ($resultat['stocksRestants'] as $stock) {
            $this->assertGreaterThanOrEqual(
                -0.000001,
                $stock['quantite']
            );
        }
    }

    public function testCompositionHomogeneAvecStockSuffisant(): void
    {
        $service = new CompositionPanierService();

        $stocks = [
            'carottes' => ['quantite' => 100.0, 'prix' => 2.0],
        ];

        $typesPaniers = [
            ['nom' => 'Simple', 'nombre' => 10, 'valeurCible' => 5.0],
            ['nom' => 'Familial', 'nombre' => 5, 'valeurCible' => 10.0],
        ];

        $resultat = $service->genererCompositionHomogene(
            $stocks,
            $typesPaniers
        );

        $this->assertEqualsWithDelta(
            2.5,
            $resultat['paniers']['Simple']['composition']['carottes'],
            0.000001
        );

        $this->assertEqualsWithDelta(
            5.0,
            $resultat['paniers']['Familial']['composition']['carottes'],
            0.000001
        );

        $this->assertEqualsWithDelta(
            50.0,
            $resultat['stocksRestants']['carottes']['quantite'],
            0.000001
        );
    }
    public function testCompositionAvecUnites(): void
    {
        $service = new CompositionPanierService();

        $stocks = [
            'carottes' => [
                'quantite' => 10.0,
                'prix' => 2.0,
                'unite' => 'kg',
            ],
            'salades' => [
                'quantite' => 5.0,
                'prix' => 1.5,
                'unite' => 'piece',
            ],
        ];

        $typesPaniers = [
            [
                'nom' => 'Simple',
                'nombre' => 5,
                'valeurCible' => 5.0,
            ],
        ];

        $resultat = $service->genererCompositionHomogene(
            $stocks,
            $typesPaniers
        );

        // La composition doit bien être générée.
        $this->assertArrayHasKey(
            'Simple',
            $resultat['paniers']
        );

        // Les unités doivent être conservées dans les stocks restants.
        $this->assertEquals(
            'kg',
            $resultat['stocksRestants']['carottes']['unite']
        );

        $this->assertEquals(
            'piece',
            $resultat['stocksRestants']['salades']['unite']
        );
    }

    public function testAjusterQuantiteEnKg(): void
    {
        $service = new CompositionPanierService();

        $resultat = $service->ajusterQuantite(0.733, 0.1);

        $this->assertEqualsWithDelta(
            0.7,
            $resultat,
            0.000001
        );
    }

    public function testAjusterQuantiteParQuartDeKg(): void
    {
        $service = new CompositionPanierService();

        $resultat = $service->ajusterQuantite(1.38, 0.25);

        $this->assertEqualsWithDelta(
            1.25,
            $resultat,
            0.000001
        );
    }

    public function testAjusterQuantiteEnPieces(): void
    {
        $service = new CompositionPanierService();

        $resultat = $service->ajusterQuantite(0.4, 1.0);

        $this->assertEqualsWithDelta(
            0.0,
            $resultat,
            0.000001
        );
    }

    public function testAjusterQuantiteEnBottes(): void
    {
        $service = new CompositionPanierService();

        $resultat = $service->ajusterQuantite(2.8, 1.0);

        $this->assertEqualsWithDelta(
            2.0,
            $resultat,
            0.000001
        );
    }
    public function testAjusterQuantiteAvecPasInvalide(): void
    {
        $service = new CompositionPanierService();

        $this->expectException(
            \InvalidArgumentException::class
        );

        $service->ajusterQuantite(2.5, 0.0);
    }

    public function testCompositionHomogeneAvecProduitEntier(): void
    {
        $service = new CompositionPanierService();

        $stocks = [
            'salades' => [
                'quantite' => 4.0,
                'prix' => 2.0,
                'unite' => 'piece',
                'pasDistribution' => 1.0,
            ],
        ];

        $typesPaniers = [
            [
                'nom' => 'Simple',
                'nombre' => 10,
                'valeurCible' => 5.0,
            ],
        ];

        $resultat = $service->genererCompositionHomogene(
            $stocks,
            $typesPaniers
        );

        // Il n'y a pas assez de salades pour en donner
        // une entière à chacun des dix paniers.
        $this->assertArrayNotHasKey(
            'salades',
            $resultat['paniers']['Simple']['composition']
        );

        // Aucune salade ne doit être déduite du stock.
        $this->assertEqualsWithDelta(
            4.0,
            $resultat['stocksRestants']['salades']['quantite'],
            0.000001
        );

        $this->assertEqualsWithDelta(
            0.0,
            $resultat['paniers']['Simple']['valeur'],
            0.001
        );
    }
    public function testCompositionHomogeneAvecPasEnKg(): void
    {
        $service = new CompositionPanierService();

        $stocks = [
            'carottes' => [
                'quantite' => 10.0,
                'prix' => 2.0,
                'unite' => 'kg',
                'pasDistribution' => 0.25,
            ],
        ];

        $typesPaniers = [
            [
                'nom' => 'Simple',
                'nombre' => 6,
                'valeurCible' => 3.0,
            ],
        ];

        $resultat = $service->genererCompositionHomogene(
            $stocks,
            $typesPaniers
        );

        // Quantité théorique : 1,5 kg par panier.
        // Elle respecte déjà le pas de 0,25 kg.
        $this->assertEqualsWithDelta(
            1.5,
            $resultat['paniers']['Simple']['composition']['carottes'],
            0.000001
        );

        // Six paniers utilisent 9 kg au total.
        $this->assertEqualsWithDelta(
            1.0,
            $resultat['stocksRestants']['carottes']['quantite'],
            0.000001
        );

        $this->assertEqualsWithDelta(
            3.0,
            $resultat['paniers']['Simple']['valeur'],
            0.001
        );
    }











}


