# the-klassiker

# The Klassiker - Site Restaurant Symfony 7

## Description du Projet

**The Klassiker** est un site web pour un restaurant de burgers et kebabs situé à Évry-Courcouronnes (France). Le site a été développé avec Symfony 7 et offre une expérience moderne et responsive pour les clients.

### Caractéristiques principales

- 🏠 **Page d'accueil** avec présentation du restaurant, menu, galerie photos
- 🍔 **Menu dynamique** avec catégories de plats gérées depuis la base de données
- 🛒 **Système de commande en ligne** avec panier, checkout et confirmation
- 👤 **Espace client** avec historique des commandes et profil
- 📸 **Galerie photos** pour présenter l'ambiance du restaurant
- 📧 **Formulaire de contact** sécurisé avec validation et notifications email
- 👨‍💼 **Dashboard administrateur** pour gérer le contenu
- 📱 **Design responsive** compatible mobile/tablette/desktop

---

## Architecture Technique

### Stack Technique

- **Framework** : Symfony 7.0
- **PHP** : 8.2+
- **Base de données** : MySQL 8
- **ORM** : Doctrine ORM
- **Templating** : Twig
- **Sécurité** : Symfony Security (bcrypt, CSRF)
- **Assets** : Webpack Encore
- **Email** : Symfony Mailer

### Structure du Projet

```
the-klassiker/
├── assets/                  # Assets frontend (Webpack)
│   ├── script/              # JavaScript
│   └── styles/             # SCSS/CSS
├── config/                  # Configuration Symfony
│   ├── packages/           # Paquets (doctrine, security, twig, etc.)
│   ├── routes.yaml
│   └── services.yaml
├── migrations/              # Migrations Doctrine
├── public/                  # Racine web
│   ├── uploads/            # Images uploadées
│   └── index.php
├── src/
│   ├── Command/            # Commandes console
│   ├── Controller/         # Contrôleurs
│   │   ├── Admin/         # CRUD administrateur
│   │   ├── MainController.php
│   │   ├── ContactController.php
│   │   ├── OrderController.php
│   │   ├── RegistrationController.php
│   │   └── SecurityController.php
│   ├── DataFixtures/       # Données initiales
│   ├── Entity/             # Entités Doctrine
│   ├── Form/               # Formulaires Symfony
│   ├── Repository/         # Repositories Doctrine
│   ├── Security/           # Authentification
│   └── Service/            # Services (Email)
└── templates/              # Templates Twig
    ├── admin/              # Templates admin
    ├── main/               # Templates publics
    ├── order/              # Templates commande
    ├── user/               # Templates utilisateur
    ├── security/           # Login
    └── email/              # Emails
```

---

## Entités Doctrine

### User (Utilisateur/Client)

| Champ     | Type     | Description                 |
| --------- | -------- | --------------------------- |
| id        | int      | Identifiant unique          |
| email     | string   | Email de connexion (unique) |
| password  | string   | Mot de passe hashé (bcrypt) |
| roles     | json     | Rôles utilisateur           |
| firstName | string   | Prénom                      |
| lastName  | string   | Nom                         |
| phone     | string   | Téléphone                   |
| address   | string   | Adresse                     |
| createdAt | datetime | Date de création            |
| updatedAt | datetime | Date de modification        |

### Category (Catégorie de plats)

| Champ        | Type      | Description         |
| ------------ | --------- | ------------------- |
| id           | int       | Identifiant unique  |
| name         | string    | Nom de la catégorie |
| slug         | string    | Slug URL (unique)   |
| description  | text      | Description         |
| displayOrder | int       | Ordre d'affichage   |
| isActive     | bool      | Actif/Inactif       |
| dishes       | OneToMany | Relation vers Dish  |

### Dish (Plat/Menu)

| Champ        | Type      | Description            |
| ------------ | --------- | ---------------------- |
| id           | int       | Identifiant unique     |
| name         | string    | Nom du plat            |
| description  | text      | Description            |
| price        | decimal   | Prix                   |
| image        | string    | Image (optionnel)      |
| displayOrder | int       | Ordre d'affichage      |
| isActive     | bool      | Actif/Inactif          |
| isFeatured   | bool      | En vedette             |
| category     | ManyToOne | Relation vers Category |
| createdAt    | datetime  | Date de création       |
| updatedAt    | datetime  | Date de modification   |

### Order (Commande)

| Champ        | Type      | Description              |
| ------------ | --------- | ------------------------ |
| id           | int       | Identifiant unique       |
| orderNumber  | string    | Numéro de commande       |
| user         | ManyToOne | Relation vers User       |
| type        | string    | Type: delivery/takeaway |
| status      | string    | Statut: pending/confirmed/preparing/ready/completed/cancelled |
| totalAmount  | decimal   | Montant total            |
| deliveryAddress | string | Adresse de livraison    |
| notes        | text      | Notes spéciales          |
| createdAt    | datetime  | Date de création         |
| updatedAt    | datetime  | Date de modification     |

### OrderItem (Article de commande)

| Champ      | Type      | Description           |
| ---------- | --------- | --------------------- |
| id         | int       | Identifiant unique    |
| order      | ManyToOne | Relation vers Order   |
| dish       | ManyToOne | Relation vers Dish    |
| quantity   | int       | Quantité               |
| unitPrice  | decimal   | Prix unitaire          |
| subtotal   | decimal   | Sous-total             |

### GalleryPhoto (Photo galerie)

| Champ        | Type     | Description        |
| ------------ | -------- | ------------------ |
| id           | int      | Identifiant unique |
| filename     | string   | Nom du fichier     |
| altText      | string   | Texte alternatif   |
| displayOrder | int      | Ordre d'affichage  |
| isActive     | bool     | Actif/Inactif      |
| createdAt    | datetime | Date de création   |

### ContactMessage (Message de contact)

| Champ     | Type     | Description           |
| --------- | -------- | --------------------- |
| id        | int      | Identifiant unique    |
| firstName | string   | Prénom                |
| lastName  | string   | Nom                   |
| email     | string   | Email                 |
| phone     | string   | Téléphone (optionnel) |
| subject   | string   | Sujet                 |
| message   | text     | Message               |
| isRead    | bool     | Lu/Non lu             |
| createdAt | datetime | Date de création      |

### OpeningHours (Horaires d'ouverture)

| Champ          | Type   | Description              |
| -------------- | ------ | ------------------------ |
| id             | int    | Identifiant unique       |
| dayName        | string | Nom du jour              |
| dayOfWeek      | int    | Jour de la semaine (0-6) |
| morningOpen    | time   | Ouverture matin          |
| morningClose   | time   | Fermeture matin          |
| afternoonOpen  | time   | Ouverture après-midi    |
| afternoonClose | time   | Fermeture après-midi    |
| isClosed       | bool   | Fermé                    |
| displayOrder   | int    | Ordre d'affichage        |

---

## Base de Données

### Schéma des Relations

```
User (1) ──────► (N) Order
Order (1) ──────► (N) OrderItem
OrderItem (N) ──► (1) Dish
Category (1) ──► (N) Dish
Dish (N) ──────► (1) Category
User (1) ──────► (N) ContactMessage
```

### Commandes Base de Données

```bash
# Créer la base de données
php bin/console doctrine:database:create

# Créer une migration après modification d'entité
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Charger les fixtures
php bin/console doctrine:fixtures:load

# Synchroniser le schéma (sans migration)
php bin/console doctrine:schema:update --force
```

---

## Routes du Site

### Routes Publiques

| Route              | Contrôleur              | Description                    |
| ------------------ | ---------------------- | ------------------------------ |
| /                  | MainController         | Page d'accueil                 |
| /contact           | ContactController      | Formulaire de contact          |
| /recrutement       | MainController         | Page recrutement               |
| /gallery           | MainController         | Galerie photos                 |
| /menu              | OrderController        | Menu avec commande             |
| /commande          | OrderController        | Page commande (alias menu)     |
| /commande/panier   | OrderController        | Panier                         |
| /commande/checkout | OrderController        | Validation commande            |
| /commande/confirmation/{id} | OrderController | Confirmation commande    |
| /login             | SecurityController     | Connexion                      |
| /register          | RegistrationController | Inscription                    |

### Routes Utilisateur (ROLE_USER)

| Route                  | Contrôleur      | Description                |
| ---------------------- | --------------- | -------------------------- |
| /profile               | UserController  | Profil utilisateur          |
| /profile/edit          | UserController  | Modifier profil            |
| /orders                | UserController  | Historique commandes       |
| /order/{id}            | UserController  | Détail commande            |

### Routes Administrateur (ROLE_ADMIN)

| Route                      | Contrôleur            | Description              |
| -------------------------- | --------------------- | ------------------------ |
| /admin/                    | DashboardController   | Dashboard admin          |
| /admin/category/           | CategoryController    | Gestion catégories      |
| /admin/dish/               | DishController        | Gestion plats            |
| /admin/photo/              | PhotoController       | Gestion galerie          |
| /admin/message/            | MessageController     | Gestion messages         |
| /admin/hours/              | HoursController       | Gestion horaires         |
| /admin/user/               | UserController        | Gestion utilisateurs     |

---

## Sécurité

### Configuration

- **Hashage des mots de passe** : Bcrypt
- **Protection CSRF** : Activée sur tous les formulaires
- **Sessions** : Natives PHP avec stockage fichiers
- **Rôles** : ROLE_ADMIN, ROLE_USER

### Accès

| Route            | Rôle requis   |
| ---------------- | ------------- |
| /                | PUBLIC_ACCESS |
| /contact         | PUBLIC_ACCESS |
| /recrutement     | PUBLIC_ACCESS |
| /gallery         | PUBLIC_ACCESS |
| /menu            | PUBLIC_ACCESS |
| /commande/*      | PUBLIC_ACCESS |
| /login           | PUBLIC_ACCESS |
| /register        | PUBLIC_ACCESS |
| /profile         | ROLE_USER     |
| /orders          | ROLE_USER     |
| /admin/*         | ROLE_ADMIN    |

### Validations

Les formulaires utilisent des validations Regex :

- **Nom/Prénom** : `^[a-zA-ZÀ-ÿ\s\-]+$` (lettres, espaces, tirets)
- **Téléphone** : Format français international
- **Email** : Validation Symfony intégrée
- **Longueurs** : Min/Max configurés par champ

---

## Fonctionnalités

### Système de Commande

1. **Menu interactif** - Sélection de plats par catégorie
2. **Panier** - Ajout/retrait d'articles avec calcul automatique
3. **Checkout** - Formulaire de livraison/à emporter
4. **Confirmation** - Email de confirmation client + notification admin
5. **Suivi** - Historique des commandes utilisateur

### Dashboard Administrateur

1. **Dashboard** - Statistiques et messages récents
2. **Catégories** - CRUD complet
3. **Plats** - CRUD complet avec gestion images
4. **Galerie** - CRUD photos
5. **Messages** - Consultation et suppression
6. **Horaire** - CRUD horaires d'ouverture

### Identifiants par défaut

```
Email : admin@theklassiker.fr
Mot de passe : Klassiker2025!
```

**⚠️ À changer immédiatement après la première connexion !**

---

## Installation

### Prérequis

- PHP 8.2+
- Composer
- MySQL 8
- Node.js (pour les assets)

### Étapes

1. **Cloner le projet**

```bash
git clone https://github.com/karlvinfdl/the-klassiker.git the-klassiker
cd the-klassiker
```

2. **Installer les dépendances**

```bash
composer install
npm install
```

3. **Configurer les variables d'environnement**

Créer un fichier `.env.local` :

```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/restaurant?serverVersion=8.0"
MAILER_DSN="smtp://user:password@smtp.example.com:587"
APP_SECRET="votre-secret-tres-long-et-aleatoire"
```

4. **Créer la base de données**

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

5. **Compiler les assets**

```bash
npm run build
```

6. **Démarrer le serveur**

```bash
php bin/console server:run
```

---

## Déploiement o2switch

### Configuration Production

1. **Fichier .env.prod**

```env
APP_ENV=prod
APP_DEBUG=0
DATABASE_URL="mysql://user:password@localhost:3306/restaurant"
MAILER_DSN="smtp://..."
APP_SECRET="secret-production"
```

2. **Permissions**

```bash
chmod -R 755 var/
chmod -R 755 public/
```

3. **Dossier public comme racine**

Configurer le chemin `/` vers `public/` dans le panel o2switch.

4. **Configuration .htaccess**

Le fichier `public/.htaccess` est déjà configuré pour Symfony.

---

## Guide d'Utilisation

### Pour les Clients

1. Créer un compte ou commander en tant qu'invité
2. Parcourir le menu et ajouter des plats au panier
3. Valider le panier et choisir : livraison ou à emporter
4. Confirmer la commande
5. Recevoir un email de confirmation
6. Suivre l'état de la commande dans l'espace client

### Pour l'Administration

1. Se connecter sur `/login`
2. Accéder au dashboard `/admin/`
3. Gérer les catégories, plats, photos, messages, horaires
4. Suivre les nouvelles commandes
5. Mettre à jour le statut des commandes

### Modification du Menu

1. Aller dans `/admin/category/` pour créer des catégories
2. Aller dans `/admin/dish/` pour ajouter des plats
3. Les plats s'afficheront automatiquement sur la page de commande

### Gestion des Images

- Les images de plats vont dans `public/uploads/`
- Les images du site sont dans `assets/images/`

---

## Commandes Utiles

```bash
# Développement
php bin/console server:run          # Serveur dev
npm run watch                       # Watch CSS/JS

# Base de données
php bin/console doctrine:schema:update --force  # Synchroniser schéma

# Cache
php bin/console cache:clear        # Vider le cache
php bin/console cache:warmup       # Préchauffer le cache

# Utilitaires
php bin/console debug:router       # Liste des routes
php bin/console debug:container     # Liste des services

# Créer un utilisateur admin
php bin/console app:create-admin-user
```

---

## Support

Pour toute question ou problème :

- Email : the.klassiker@gmail.com
- Consulter la documentation Symfony : https://symfony.com/doc/

---

## Licence

Propriétaire - The Klassiker © 2025

