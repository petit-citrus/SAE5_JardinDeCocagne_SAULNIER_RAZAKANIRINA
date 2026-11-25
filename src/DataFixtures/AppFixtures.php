<?php

namespace App\DataFixtures;

use App\Entity\AbonnementClient;
use App\Entity\Adhesion;
use App\Entity\CalendrierDiffusion;
use App\Entity\CategorieClient;
use App\Entity\Commande;
use App\Entity\CompositionPanier;
use App\Entity\DecalageJourFerie;
use App\Entity\ExceptionCalendrier;
use App\Entity\Facture;
use App\Entity\FeuilleDeChou;
use App\Entity\FormuleAbonnement;
use App\Entity\Frequence;
use App\Entity\JourPreparation;
use App\Entity\LigneCompositionPanier;
use App\Entity\LigneLivraison;
use App\Entity\LivraisonUnitaire;
use App\Entity\PasserelleIotSerre;
use App\Entity\PointDepot;
use App\Entity\Produit;
use App\Entity\Recette;
use App\Entity\Reglement;
use App\Entity\Role;
use App\Entity\Saison;
use App\Entity\SemaineFermeture;
use App\Entity\StockLegume;
use App\Entity\Structure;
use App\Entity\TarifCategorieClient;
use App\Entity\Tournee;
use App\Entity\TourneePointDepot;
use App\Entity\TypeAdhesion;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $thaon = $this->createStructure(
            'Jardins de Cocagne de Thaon-les-Vosges',
            '12345678901234',
            'FR7630006000011234567890189',
            true,
        );
        $angevin = $this->createStructure(
            'Le Jardin de Cocagne Angevin',
            '98765432109876',
            'FR7630006000019876543210189',
            false,
        );
        $manager->persist($thaon);
        $manager->persist($angevin);
        $manager->flush();

        $permissions = [
            'Admin' => ['*'],
            'Gestionnaire' => ['utilisateurs.lire', 'commandes.gerer', 'paniers.gerer'],
            'Bénévole' => ['commandes.lire', 'livraisons.preparer'],
            'Client' => ['commandes.creer', 'abonnements.lire'],
        ];
        $roles = [];
        $categories = [];

        foreach (['thaon' => $thaon, 'angevin' => $angevin] as $key => $structure) {
            foreach ($permissions as $nom => $droits) {
                $role = (new Role())
                    ->setNom($nom)
                    ->setPermissions($droits)
                    ->setStructure($structure);
                $manager->persist($role);
                $roles[$key][$nom] = $role;
            }

            foreach (['Particulier', 'Salarié en insertion', 'Restauration collective'] as $nom) {
                $categorie = (new CategorieClient())
                    ->setNom($nom)
                    ->setStructure($structure);
                $manager->persist($categorie);
                $categories[$key][$nom] = $categorie;
            }
        }

        $passwordHash = password_hash('CocagneTest2026!', PASSWORD_DEFAULT);
        if ($passwordHash === false) {
            throw new \RuntimeException('Impossible de hacher le mot de passe de démonstration.');
        }

        $admin = $this->createUser(
            'Martin',
            'Claire',
            'claire.admin@cocagne.test',
            $passwordHash,
            '06 10 20 30 40',
            '12 rue des Jardins, 88150 Thaon-les-Vosges',
            $thaon,
            null,
            $roles['thaon']['Admin'],
        );
        $manager->persist($admin);

        $gestionnaire = $this->createUser(
            'Petit',
            'Thomas',
            'thomas.gestionnaire@cocagne.test',
            $passwordHash,
            '06 11 22 33 44',
            '8 avenue de la Gare, 88150 Thaon-les-Vosges',
            $thaon,
            null,
            $roles['thaon']['Gestionnaire'],
        );
        $manager->persist($gestionnaire);

        $benevole = $this->createUser(
            'Leroy',
            'Sophie',
            'sophie.benevole@cocagne.test',
            $passwordHash,
            '06 12 23 34 45',
            '3 rue des Vosges, 88000 Épinal',
            $thaon,
            null,
            $roles['thaon']['Bénévole'],
        );
        $manager->persist($benevole);

        $particulier = $this->createUser(
            'Bernard',
            'Élodie',
            'elodie.bernard@cocagne.test',
            $passwordHash,
            '06 13 24 35 46',
            '5 rue des Lilas, 88150 Thaon-les-Vosges',
            $thaon,
            $categories['thaon']['Particulier'],
            $roles['thaon']['Client'],
        );
        $manager->persist($particulier);

        $salarie = $this->createUser(
            'Robert',
            'Yanis',
            'yanis.robert@cocagne.test',
            $passwordHash,
            '06 14 25 36 47',
            '17 rue de la Prairie, 88000 Épinal',
            $thaon,
            $categories['thaon']['Salarié en insertion'],
            $roles['thaon']['Client'],
        );
        $manager->persist($salarie);

        $restauration = $this->createUser(
            'Moreau',
            'Isabelle',
            'isabelle.moreau@cocagne.test',
            $passwordHash,
            '03 29 00 10 20',
            '1 place du Marché, 88000 Épinal',
            $thaon,
            $categories['thaon']['Restauration collective'],
            $roles['thaon']['Client'],
        );
        $manager->persist($restauration);

        $angevinManager = $this->createUser(
            'Dubois',
            'Julien',
            'julien.gestionnaire@angevin.cocagne.test',
            $passwordHash,
            '02 41 00 11 22',
            '2 chemin des Prés, 49000 Angers',
            $angevin,
            null,
            $roles['angevin']['Gestionnaire'],
        );
        $manager->persist($angevinManager);
        $manager->persist($this->createUser(
            'Simon',
            'Nathalie',
            'nathalie.simon@angevin.cocagne.test',
            $passwordHash,
            '06 20 30 40 50',
            '6 rue des Ardoisières, 49000 Angers',
            $angevin,
            $categories['angevin']['Particulier'],
            $roles['angevin']['Client'],
        ));

        $saison = $this->createSeason($thaon);
        $manager->persist($saison);

        foreach ([52, 31] as $numeroSemaine) {
            $manager->persist((new SemaineFermeture())
                ->setNumeroSemaine($numeroSemaine)
                ->setAnnee($numeroSemaine === 52 ? 2026 : 2027)
                ->setSaison($saison));
        }

        $adhesionType = (new TypeAdhesion())
            ->setLibelle('Adhésion annuelle')
            ->setMontant(25.0)
            ->setPeriodicite('annuelle')
            ->setDateCharniere(new \DateTimeImmutable('2027-08-31'))
            ->setStructure($thaon);
        $manager->persist($adhesionType);

        $prepDays = [];
        foreach ([1 => 'lundi', 3 => 'mercredi', 5 => 'vendredi'] as $day => $label) {
            $prepDay = (new JourPreparation())
                ->setJourSemaine($day)
                ->setStructure($thaon);
            $manager->persist($prepDay);
            $prepDays[$label] = $prepDay;
        }

        $routes = [];
        foreach ([
            'lundi' => ['Tournée centre-ville', '#4E9A51'],
            'mercredi' => ['Tournée vallée de la Moselle', '#4A78B8'],
            'vendredi' => ['Tournée entreprises et professionnels', '#D18B32'],
        ] as $day => [$identifiant, $couleur]) {
            $route = (new Tournee())
                ->setIdentifiant($identifiant)
                ->setCouleur($couleur)
                ->setStructure($thaon)
                ->setJourPreparation($prepDays[$day]);
            $manager->persist($route);
            $routes[$day] = $route;
        }

        $points = [];
        $points['public'] = $this->createDepot(
            'Maison des Associations',
            '10 rue de la République, 88150 Thaon-les-Vosges',
            'Accueil des adhérents',
            '03 29 39 10 10',
            80,
            'ouvert à tous',
            null,
            $thaon,
        );
        $points['entreprise'] = $this->createDepot(
            'Comité d’entreprise textile',
            '25 rue d’Alsace, 88150 Thaon-les-Vosges',
            'Accueil du personnel',
            '03 29 39 20 20',
            45,
            'entreprise',
            'AccesEntreprise2026',
            $thaon,
            $categories['thaon']['Restauration collective'],
        );
        $points['pro'] = $this->createDepot(
            'Cuisine centrale d’Épinal',
            '4 avenue de la Logistique, 88000 Épinal',
            'Isabelle Moreau',
            '03 29 39 30 30',
            120,
            'pro',
            null,
            $thaon,
            $categories['thaon']['Restauration collective'],
        );
        $points['marche'] = $this->createDepot(
            'Marché couvert de Thaon',
            'Place de la Victoire, 88150 Thaon-les-Vosges',
            'Équipe du jardin',
            '03 29 39 40 40',
            35,
            'ouvert à tous',
            null,
            $thaon,
        );
        foreach ($points as $point) {
            $manager->persist($point);
        }

        $routePoints = [];
        foreach ([
            ['lundi', 'public', 1, '08:00-08:30', '12:00-18:00'],
            ['lundi', 'pro', 2, '08:35-09:00', '10:00-12:00'],
            ['mercredi', 'public', 1, '08:00-08:30', '12:00-18:00'],
            ['mercredi', 'marche', 2, '08:35-09:00', '09:00-12:00'],
            ['vendredi', 'entreprise', 1, '08:00-08:30', '12:00-17:00'],
            ['vendredi', 'pro', 2, '08:35-09:00', '10:00-12:00'],
        ] as [$routeName, $pointName, $order, $internalSlot, $clientSlot]) {
            $routePoint = (new TourneePointDepot())
                ->setOrdreLivraison($order)
                ->setCreneauInterne($internalSlot)
                ->setCreneauClient($clientSlot)
                ->setTournee($routes[$routeName])
                ->setPointDepot($points[$pointName]);
            $manager->persist($routePoint);
            $routePoints[$routeName . '_' . $pointName] = $routePoint;
        }

        foreach ([
            [$routes['vendredi'], '2026-12-25', '2026-12-28'],
            [$routes['vendredi'], '2027-01-01', '2027-01-04'],
        ] as [$route, $holiday, $reportDate]) {
            $manager->persist((new DecalageJourFerie())
                ->setDateFerie(new \DateTimeImmutable($holiday))
                ->setDateReport(new \DateTimeImmutable($reportDate))
                ->setSaison($saison)
                ->setTournee($route));
        }

        $frequencies = [];
        foreach ([
            'hebdomadaire' => ['Hebdomadaire', 'FREQ=WEEKLY;BYDAY=WE'],
            'quinzomadaire' => ['Tous les quinze jours', 'FREQ=WEEKLY;INTERVAL=2;BYDAY=FR'],
            'mensuelle' => ['Mensuelle', 'FREQ=MONTHLY;BYDAY=1MO'],
        ] as $key => [$label, $rule]) {
            $frequency = (new Frequence())
                ->setLibelle($label)
                ->setRruleBase($rule)
                ->setStructure($thaon);
            $manager->persist($frequency);
            $frequencies[$key] = $frequency;
        }

        $vegetableData = [
            'carottes' => ['Carottes', 'kg', 2.40, 180.0],
            'pommes-de-terre' => ['Pommes de terre', 'kg', 1.90, 260.0],
            'poireaux' => ['Poireaux', 'botte', 2.80, 90.0],
            'salades' => ['Salades', 'pièce', 1.50, 75.0],
            'oignons' => ['Oignons', 'kg', 2.20, 100.0],
            'courges' => ['Courges', 'kg', 2.60, 130.0],
            'betteraves' => ['Betteraves', 'kg', 2.30, 85.0],
            'choux' => ['Choux', 'pièce', 2.10, 70.0],
            'navets' => ['Navets', 'botte', 2.50, 55.0],
            'radis-noirs' => ['Radis noirs', 'pièce', 1.40, 45.0],
        ];
        $vegetables = [];
        foreach ($vegetableData as $key => [$name, $unit, $price, $stock]) {
            $vegetable = $this->createProduct($name, 'legume', $unit, $price, $thaon);
            $manager->persist($vegetable);
            $manager->persist((new StockLegume())
                ->setQuantite($stock)
                ->setPeriode('automne-hiver 2026-2027')
                ->setStructure($thaon)
                ->setProduit($vegetable));
            $vegetables[$key] = $vegetable;
        }

        $fruits = [];
        foreach ([
            'pommes' => ['Pommes', 'kg', 3.20, 100.0],
            'poires' => ['Poires', 'kg', 3.60, 65.0],
        ] as $key => [$name, $unit, $price, $stock]) {
            $fruit = $this->createProduct($name, 'fruit', $unit, $price, $thaon);
            $manager->persist($fruit);
            $manager->persist((new StockLegume())
                ->setQuantite($stock)
                ->setPeriode('automne-hiver 2026-2027')
                ->setStructure($thaon)
                ->setProduit($fruit));
            $fruits[$key] = $fruit;
        }

        $baskets = [];
        foreach ([
            'simple' => ['Panier simple', 14.00],
            'familial' => ['Panier familial', 24.00],
            'fruite' => ['Panier fruité', 19.50],
        ] as $key => [$name, $price]) {
            $basket = $this->createProduct($name, 'panier', 'panier', $price, $thaon);
            $manager->persist($basket);
            $baskets[$key] = $basket;
        }

        foreach ($vegetables + $fruits + $baskets as $product) {
            foreach ([
                'Particulier' => 1.0,
                'Salarié en insertion' => 0.9,
                'Restauration collective' => 1.15,
            ] as $categoryName => $factor) {
                $manager->persist((new TarifCategorieClient())
                    ->setPrixSpecifique(round($product->getPrixHt() * $factor, 2))
                    ->setProduit($product)
                    ->setCategorieClient($categories['thaon'][$categoryName]));
            }
        }
        $manager->flush();

        $compositionItems = [
            'simple' => ['carottes' => 1.0, 'pommes-de-terre' => 1.0, 'poireaux' => 1.0, 'salades' => 1.0],
            'familial' => ['carottes' => 2.0, 'pommes-de-terre' => 2.0, 'oignons' => 1.0, 'courges' => 1.5, 'choux' => 1.0],
            'fruite' => ['pommes' => 1.0, 'poires' => 1.0, 'carottes' => 1.0, 'betteraves' => 1.0],
        ];
        $produce = $vegetables + $fruits;
        $compositions = [];
        foreach ($compositionItems as $basketKey => $items) {
            $route = match ($basketKey) {
                'simple' => $routes['mercredi'],
                'familial' => $routes['vendredi'],
                default => $routes['lundi'],
            };
            $composition = (new CompositionPanier())
                ->setContenu($this->basketPayload($items, $produce))
                ->setCoutTotal($baskets[$basketKey]->getPrixHt())
                ->setValide(true)
                ->setProduit($baskets[$basketKey])
                ->setSaison($saison)
                ->setTournee($route);
            $manager->persist($composition);
            $compositions[$basketKey] = $composition;

            foreach ($items as $vegetableKey => $quantity) {
                $manager->persist((new LigneCompositionPanier())
                    ->setQuantite($quantity)
                    ->setCompositionPanier($composition)
                    ->setProduit($produce[$vegetableKey]));
            }
        }

        $calendarDefinitions = [
            'hebdomadaire' => [$frequencies['hebdomadaire'], $routes['mercredi'], 'WE', 'toutes', 28],
            'quinzomadaire' => [$frequencies['quinzomadaire'], $routes['vendredi'], 'FR', 'impaire', 18],
            'mensuelle' => [$frequencies['mensuelle'], $routes['lundi'], 'MO', 'toutes', 12],
        ];
        $calendars = [];
        foreach ($calendarDefinitions as $key => [$frequency, $route, $weekday, $parity, $numberOfBaskets]) {
            $calendar = (new CalendrierDiffusion())
                ->setRruleComplete('RRULE:' . $frequency->getRruleBase())
                ->setPariteDepart($parity)
                ->setNbPaniers($numberOfBaskets)
                ->setSaison($saison)
                ->setTournee($route)
                ->setFrequence($frequency);
            $manager->persist($calendar);
            $calendars[$key] = $calendar;
        }
        $manager->persist((new ExceptionCalendrier())
            ->setDateOriginale(new \DateTimeImmutable('2026-12-23'))
            ->setDateModifiee(new \DateTimeImmutable('2026-12-24'))
            ->setCalendrierDiffusion($calendars['hebdomadaire']));

        $plans = [];
        foreach ([
            'simple' => ['simple', 'hebdomadaire'],
            'familial' => ['familial', 'quinzomadaire'],
            'fruite' => ['fruite', 'mensuelle'],
        ] as $key => [$basketKey, $frequencyKey]) {
            $plan = (new FormuleAbonnement())
                ->setStructure($thaon)
                ->setProduit($baskets[$basketKey])
                ->setFrequence($frequencies[$frequencyKey]);
            $manager->persist($plan);
            $plans[$key] = $plan;
        }

        $subscriptions = [];
        foreach ([
            'particulier' => [$particulier, 'simple', 'hebdomadaire', 'mercredi_public', '14.00 € / panier'],
            'salarie' => [$salarie, 'familial', 'quinzomadaire', 'vendredi_entreprise', '24.00 € / panier'],
            'restauration' => [$restauration, 'fruite', 'mensuelle', 'lundi_pro', '19.50 € / panier'],
        ] as $key => [$user, $planKey, $frequencyKey, $routePointKey, $appliedRate]) {
            $subscription = (new AbonnementClient())
                ->setDateDebut(new \DateTimeImmutable('2026-09-01'))
                ->setDateFin(null)
                ->setStatut('actif')
                ->setTarifApplique($appliedRate)
                ->setUtilisateur($user)
                ->setSaison($saison)
                ->setFormuleAbonnement($plans[$planKey])
                ->setTourneePointDepot($routePoints[$routePointKey])
                ->setCalendrierDiffusion($calendars[$frequencyKey]);
            $manager->persist($subscription);
            $subscriptions[$key] = $subscription;
        }

        $adhesions = [];
        foreach ([$particulier, $salarie, $restauration] as $index => $user) {
            $adhesion = (new Adhesion())
                ->setDateDebut(new \DateTimeImmutable('2026-09-01'))
                ->setDateFin(new \DateTimeImmutable('2027-08-31'))
                ->setMontant(25.0)
                ->setStatut('payée')
                ->setUtilisateur($user)
                ->setTypeAdhesion($adhesionType)
                ->setSaison($saison);
            $manager->persist($adhesion);
            $adhesions[$index] = $adhesion;
        }

        $orders = [];
        foreach ([
            'particulier' => ['simple', 'hebdomadaire', 'mercredi_public', '14.77'],
            'salarie' => ['familial', 'quinzomadaire', 'vendredi_entreprise', '25.32'],
        ] as $key => [$basketKey, $frequencyKey, $routePointKey, $total]) {
            $order = (new Commande())
                ->setDateCommande(new \DateTimeImmutable('2026-11-20 10:30:00'))
                ->setTotalTtc((float) $total)
                ->setFraisLivraison(0.0)
                ->setUtilisateur($subscriptions[$key]->getUtilisateur())
                ->setStructure($thaon)
                ->setAbonnementClient($subscriptions[$key]);
            $manager->persist($order);

            $delivery = (new LivraisonUnitaire())
                ->setDatePrevue(new \DateTimeImmutable($frequencyKey === 'hebdomadaire' ? '2026-11-25' : '2026-11-27'))
                ->setDateEffective(null)
                ->setStatut('prévue')
                ->setCommande($order)
                ->setAbonnementClient($subscriptions[$key])
                ->setTourneePointDepot($routePoints[$routePointKey])
                ->setCompositionPanier($compositions[$basketKey]);
            $manager->persist($delivery);

            foreach (array_slice($compositionItems[$basketKey], 0, 3, true) as $vegetableKey => $quantity) {
                $manager->persist((new LigneLivraison())
                    ->setQuantite($quantity)
                    ->setLivraisonUnitaire($delivery)
                    ->setProduit($vegetables[$vegetableKey]));
            }
            $orders[$key] = $order;
        }

        $invoiceOrder = (new Facture())
            ->setNumero('FAC-2026-0001')
            ->setMontantHt(14.00)
            ->setMontantTva(0.77)
            ->setMontantTtc(14.77)
            ->setHashSecurite(hash('sha256', 'fixture-invoice-2026-0001'))
            ->setStructure($thaon)
            ->setUtilisateur($particulier)
            ->setCommande($orders['particulier']);
        $manager->persist($invoiceOrder);

        $invoiceMembership = (new Facture())
            ->setNumero('FAC-2026-0002')
            ->setMontantHt(25.00)
            ->setMontantTva(0.00)
            ->setMontantTtc(25.00)
            ->setHashSecurite(hash('sha256', 'fixture-invoice-2026-0002'))
            ->setStructure($thaon)
            ->setUtilisateur($particulier)
            ->setAdhesion($adhesions[0]);
        $manager->persist($invoiceMembership);

        foreach ([
            [$invoiceOrder, 14.77, 'carte bancaire'],
            [$invoiceMembership, 25.00, 'virement'],
        ] as [$invoice, $amount, $paymentMethod]) {
            $manager->persist((new Reglement())
                ->setDateReglement(new \DateTimeImmutable('2026-11-20'))
                ->setMontant($amount)
                ->setMoyenPaiement($paymentMethod)
                ->setStatut('validé')
                ->setUtilisateur($particulier)
                ->setFacture($invoice));
        }

        $recipe = (new Recette())
            ->setNom('Soupe de courge et poireaux')
            ->setInstructions('Faire revenir les oignons, ajouter la courge et les poireaux en morceaux, couvrir de bouillon et cuire 25 minutes. Mixer et assaisonner.')
            ->setStructureAuteureId($thaon->getId());
        $recipe->addProduit($vegetables['courges']);
        $recipe->addProduit($vegetables['poireaux']);
        $recipe->addProduit($vegetables['oignons']);
        $manager->persist($recipe);

        $manager->persist((new FeuilleDeChou())
            ->setTitre('La récolte d’automne au jardin')
            ->setContenu('Les carottes, courges et poireaux sont à l’honneur cette semaine. Pensez à conserver les légumes au frais et à cuisiner les fanes rapidement.')
            ->setStructure($thaon)
            ->setSaison($saison)
            ->setProduit($vegetables['courges'])
            ->setTournee($routes['mercredi']));

        $manager->persist((new PasserelleIotSerre())
            ->setNomSerre('Serre pédagogique nord')
            ->setUrlApi('https://iot.example.test/api/serres/nord')
            ->setApiKey('fixture-demo-key-not-valid')
            ->setStructure($thaon));

        $manager->flush();
    }

    private function createStructure(string $name, string $siret, string $iban, bool $vatSubject): Structure
    {
        return (new Structure())
            ->setNom($name)
            ->setSiret($siret)
            ->setIban($iban)
            ->setAssujettiTva($vatSubject);
    }

    private function createSeason(Structure $structure): Saison
    {
        return (new Saison())
            ->setLibelle('Saison 2026-2027')
            ->setDateDebut(new \DateTimeImmutable('2026-09-01'))
            ->setDateFin(new \DateTimeImmutable('2027-08-31'))
            ->setCloture(false)
            ->setStructure($structure);
    }

    private function createUser(
        string $lastName,
        string $firstName,
        string $email,
        string $passwordHash,
        string $phone,
        string $address,
        Structure $structure,
        ?CategorieClient $category,
        Role $role,
    ): Utilisateur {
        $user = (new Utilisateur())
            ->setNom($lastName)
            ->setPrenom($firstName)
            ->setEmail($email)
            ->setPassword($passwordHash)
            ->setTelephone($phone)
            ->setAdresse($address)
            ->setDispenseAdhesion(false)
            ->setSolde(0.0)
            ->setStructure($structure)
            ->setCategorieClient($category);
        $user->addRole($role);

        return $user;
    }

    private function createDepot(
        string $name,
        string $address,
        string $contactName,
        string $phone,
        int $capacity,
        string $category,
        ?string $password,
        Structure $structure,
        ?CategorieClient $clientCategory = null,
    ): PointDepot {
        return (new PointDepot())
            ->setNom($name)
            ->setAdresse($address)
            ->setContactNom($contactName)
            ->setContactTel($phone)
            ->setCapaciteMax($capacity)
            ->setCategorie($category)
            ->setMotDePasse($password)
            ->setStructure($structure)
            ->setCategorieClient($clientCategory);
    }

    private function createProduct(string $name, string $type, string $unit, float $price, Structure $structure): Produit
    {
        return (new Produit())
            ->setNom($name)
            ->setType($type)
            ->setUnite($unit)
            ->setPrixHt($price)
            ->setTva(5.5)
            ->setMarge(0.2)
            ->setPhoto(null)
            ->setStructure($structure);
    }

    /**
     * @param array<string, float> $items
     * @param array<string, Produit> $products
     *
     * @return array{items: list<array{produitId: int|null, nom: string|null, quantite: float, unite: string|null}>}
     */
    private function basketPayload(array $items, array $products): array
    {
        $payloadItems = [];
        foreach ($items as $productKey => $quantity) {
            $product = $products[$productKey];
            $payloadItems[] = [
                'produitId' => $product->getId(),
                'nom' => $product->getNom(),
                'quantite' => $quantity,
                'unite' => $product->getUnite(),
            ];
        }

        return ['items' => $payloadItems];
    }
}
