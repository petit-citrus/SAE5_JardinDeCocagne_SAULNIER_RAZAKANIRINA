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

        // Ouvrir le formulaire pour récupérer son jeton CSRF.
        $crawler = $client->request('GET', '/paniers');

        $token = $crawler
            ->filter('input[name="_csrf_token"]')
            ->attr('value');

        $this->assertNotEmpty($token);

        // Envoyer une quantité négative avec un jeton valide.
        $client->request('POST', '/paniers', [
            '_csrf_token' => $token,
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

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains(
            '.paniers-erreurs',
            'Quantité invalide pour : carottes'
        );

        $this->assertSelectorNotExists(
            '#resultats-paniers'
        );
    }

    // Test 5 : vérifier une soumission sans jeton CSRF.
    public function testFormulaireSansJetonCsrf(): void
    {
        $client = static::createClient();

        // Envoyer une requête POST sans jeton CSRF.
        $client->request('POST', '/paniers', [
            'stocks' => [
                'carottes' => '10',
                'pommes_de_terre' => '15',
                'poireaux' => '8',
            ],
            'paniers' => [
                'Simple' => '10',
                'Familial' => '5',
            ],
        ]);

        $this->assertResponseIsSuccessful();

        // Vérifier que le serveur refuse la soumission.
        $this->assertSelectorTextContains(
            '.paniers-erreurs',
            'Jeton de sécurité invalide'
        );

        // Vérifier qu'aucune composition n'est générée.
        $this->assertSelectorNotExists(
            '#resultats-paniers'
        );
    }

    public function testAffichageDiversiteInsuffisante(): void
    {
        $client = static::createClient();

        // Ouvrir le formulaire.
        $client->request('GET', '/paniers');

        // Générer des paniers avec uniquement des carottes.
        $client->submitForm('Générer les compositions', [
            'stocks[carottes]' => '100',
            'stocks[pommes_de_terre]' => '0',
            'stocks[poireaux]' => '0',
            'paniers[Simple]' => '10',
            'paniers[Familial]' => '5',
        ]);

        $this->assertResponseIsSuccessful();

        // Les deux types de paniers doivent afficher une alerte.
        $this->assertSelectorCount(
            2,
            '.paniers-diversite-alerte'
        );

        // Aucun panier ne doit être déclaré suffisamment diversifié.
        $this->assertSelectorNotExists(
            '.paniers-diversite-ok'
        );
    }



}
