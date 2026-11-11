<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PanierControllerTest extends WebTestCase
{
    // Test 1 : vérifier que la page fonctionne.
    public function testPagePaniersAccessible(): void
    {
        $client = static::createClient();

        $client->request('GET', '/paniers');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains(
            'h1',
            'Composition des paniers'
        );

        $this->assertSelectorExists(
            'form[method="post"]'
        );
    }

    // Test 2 : vérifier une soumission avec zéro stock.
    public function testFormulaireAvecStocksVides(): void
    {
        $client = static::createClient();

        $client->request('GET', '/paniers');

        $client->submitForm('Générer les compositions', [
            'stocks[carottes]' => '0',
            'stocks[pommes_de_terre]' => '0',
            'stocks[poireaux]' => '0',
            'paniers[Simple]' => '10',
            'paniers[Familial]' => '5',
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains(
            '#resultats-paniers',
            'Coefficient de répartition'
        );

        $this->assertStringContainsString(
            '0,0 %',
            $client->getResponse()->getContent()
        );

        $this->assertSelectorCount(
            2,
            '.paniers-composition'
        );
    }

    // Test 3 : vérifier des stocks suffisants.
    public function testFormulaireAvecStockSuffisant(): void
    {
        $client = static::createClient();

        $client->request('GET', '/paniers');

        $client->submitForm('Générer les compositions', [
            'stocks[carottes]' => '100',
            'stocks[pommes_de_terre]' => '0',
            'stocks[poireaux]' => '0',
            'paniers[Simple]' => '10',
            'paniers[Familial]' => '5',
        ]);

        $this->assertResponseIsSuccessful();

        $contenu = $client->getResponse()->getContent();

        $this->assertStringContainsString(
            '100,0 %',
            $contenu
        );

        $this->assertSelectorCount(
            2,
            '.paniers-composition'
        );

        $this->assertSelectorTextContains(
            '.paniers-composition',
            '5,00 €'
        );
    }

    //Test 4 : vérifier une soumission avec un stock négatif.
    public function testFormulaireAvecStockNegatif(): void
    {
        $client = static::createClient();

        $client->request('POST', '/paniers', [
            'stocks' => [
                'carottes' => '-5',
                'pommes_de_terre' => '15',
                'poireaux' => '8',
            ],
            'paniers' => [
                'Simple' => '10',
                'Familial' => '5',
            ],
        ]);

        // Le serveur répond, mais refuse les données invalides.
        $this->assertResponseIsSuccessful();

        // Un message d'erreur doit être affiché.
        $this->assertSelectorTextContains(
            '.paniers-erreurs',
            'Quantité invalide pour : carottes'
        );

        // Aucun résultat ne doit être généré.
        $this->assertSelectorNotExists(
            '#resultats-paniers'
        );
    }

}
