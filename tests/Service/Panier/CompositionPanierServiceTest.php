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



}


