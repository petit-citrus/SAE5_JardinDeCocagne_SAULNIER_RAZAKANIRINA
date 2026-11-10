# R15 — Règles métier : composition des paniers

## 1. Contexte

Le Réseau Cocagne souhaite disposer d'un outil permettant de faciliter la création et l'optimisation des paniers de légumes.

Actuellement, la composition des paniers est réalisée à partir des légumes disponibles, des types de paniers et des valeurs économiques attendues.

Le nouvel outil doit proposer une répartition équitable et homogène des légumes, tout en cherchant à écouler au mieux les stocks disponibles.

## 2. Objectifs fonctionnels

Le module doit permettre de :

- Renseigner les légumes disponibles et leurs quantités en stock.
- Définir les différents types de paniers.
- Indiquer le nombre de paniers à préparer pour chaque type.
- Définir la valeur cible des paniers.
- Proposer automatiquement une composition des paniers.
- Vérifier que les quantités proposées ne dépassent pas les stocks disponibles.
- Afficher les résultats pour permettre leur vérification et leur ajustement.

## 3. Données nécessaires

### 3.1. Produits

Chaque produit possède au minimum :

- Un identifiant.
- Un nom.
- Une unité de mesure (kg, pièce, botte, etc.).
- Un prix de référence.
- Une quantité disponible en stock.

### 3.2. Types de paniers

Chaque type de panier possède :

- Un identifiant.
- Un nom (Simple, Familial, etc.).
- Une valeur cible.
- Un nombre de paniers à préparer.

### 3.3. Composition

Chaque ligne de composition représente :

- Un type de panier.
- Un produit.
- Une quantité par panier.
- Une valeur calculée selon la quantité et le prix de référence.

La composition est associée à une date de préparation ou de distribution.

## 4. Règles métier

### RM01 — Respect des stocks

La quantité totale distribuée d'un produit ne doit jamais dépasser la quantité disponible.

Pour un produit donné :

**Quantité consommée = somme (quantité par panier × nombre de paniers du type concerné)**

La quantité consommée doit rester inférieure ou égale au stock disponible.

### RM02 — Respect de la valeur cible

La valeur de chaque type de panier doit se rapprocher autant que possible de sa valeur cible.

**Valeur du panier = somme des valeurs des produits qui le composent.**

La référence de prix utilisée et la tolérance acceptable restent à définir.

### RM03 — Répartition équitable et homogène

Pour une même préparation, les paniers d'un même type doivent en principe avoir une composition identique.

L'algorithme doit chercher une répartition équilibrée entre les différents types de paniers.

### RM04 — Optimisation des stocks

L'algorithme doit chercher à utiliser au mieux les légumes disponibles sans dépasser les quantités en stock.

Si tous les légumes ne peuvent pas être distribués, le système doit identifier les quantités restantes.

### RM05 — Gestion des unités

Les produits peuvent être mesurés dans différentes unités : kilogrammes, pièces ou bottes.

Les quantités doivent être calculées et comparées dans des unités compatibles.

### RM06 — Vérification des résultats

Le système doit permettre de consulter :

- La composition proposée pour chaque type de panier.
- La valeur totale calculée.
- L'écart avec la valeur cible.
- Les quantités totales nécessaires.
- Le stock restant après répartition.

### RM07 — Gestion des situations impossibles

Lorsque le stock disponible ne permet pas de satisfaire toutes les contraintes, le système ne doit pas produire silencieusement une composition invalide.

Il doit signaler les contraintes non satisfaites et les écarts constatés.

## 5. Exemple de fonctionnement

*Exemple fictif destiné à tester l'algorithme.*

### Stocks disponibles

| Produit | Prix unitaire | Stock |
|---|---:|---:|
| Carottes | 2 €/kg | 30 kg |
| Pommes de terre | 1,50 €/kg | 40 kg |
| Poireaux | 3 €/kg | 20 kg |

### Paniers à préparer

| Type | Quantité | Valeur cible |
|---|---:|---:|
| Simple | 10 | 5 € |
| Familial | 5 | 10 € |

L'algorithme doit proposer une composition respectant les stocks, tout en rapprochant chaque type de panier de sa valeur cible.

