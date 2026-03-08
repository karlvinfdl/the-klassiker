# Plan de création de la page de commande - The Klassiker

## Phase 1: Entités (Database)

- [x] Créer Order.php - Entité commande (client, type, adresse, total, status)
- [x] Créer OrderItem.php - Articles de la commande
- [x] Créer migration

## Phase 2: Formulaires

- [x] Créer OrderType.php - Formulaire de commande client

## Phase 3: Contrôleur

- [x] Créer OrderController.php
- [x] Route /commande (menu + panier)
- [x] Route /commande/ajouter (ajout article)
- [x] Route /commande/panier
- [x] Route /commande/checkout
- [x] Route /commande/confirmation

## Phase 4: Templates

- [x] templates/order/index.html.twig - Menu avec ajout rapide
- [x] templates/order/panier.html.twig - Panier
- [x] templates/order/checkout.html.twig - Formulaire client
- [x] templates/order/confirmation.html.twig - Confirmation

## Phase 5: Service & Email

- [x] Mettre à jour EmailService.php pour envoyer les commandes
- [x] Template email order_confirmation.html.twig
- [x] Template email new_order_admin.html.twig

## Phase 6: Navigation

- [x] Mettre à jour base.html.twig - lien vers commande
- [x] Ajouter flashes messages pour les alertes

## Étapes pour activer :

1. Exécuter la migration : `php bin/console doctrine:migrations:migrate`
2. Vider le cache : `php bin/console cache:clear`
3. Tester la page /commande
