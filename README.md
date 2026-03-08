# the-klassiker

# The Klassiker - Site Restaurant Symfony 7

## Description du Projet

**The Klassiker** est un site web pour un restaurant de burgers et kebabs situé à Évry-Courcouronnes (France). Le site a été développé avec Symfony 7 et offre une expérience moderne et responsive pour les clients.

### Caractéristiques principales

- 🏠 **Page d'accueil** avec présentation du restaurant, menu, galerie photos
- 🍔 **Menu dynamique** avec catégories de plats gérées depuis la base de données
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
│   │   └── SecurityController.php
│   ├── DataFixtures/       # Données initiales
│   ├── Entity/             # Entités Doctrine
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Dish.php
│   │   ├── GalleryPhoto.php
│   │   ├── ContactMessage.php
│   │   └── OpeningHours.php
│   ├── Form/               # Formulaires Symfony
│   ├── Repository/         # Repositories Doctrine
│   ├── Security/           # Authentification
│   └── Service/            # Services (Email)
└── templates/              # Templates Twig
    ├── admin/              # Templates admin
    ├── main/               # Templates publics
    ├── security/           # Login
    └── email/              # Emails
```

---

## Entités Doctrine

### User (Administrateur)

| Champ     | Type     | Description                 |
| --------- | -------- | --------------------------- |
| id        | int      | Identifiant unique          |
| email     | string   | Email de connexion (unique) |
| password  | string   | Mot de passe hashé (bcrypt) |
| roles     | json     | Rôles utilisateur           |
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
| afternoonOpen  | time   | Ouverture après-midi     |
| afternoonClose | time   | Fermeture après-midi     |
| isClosed       | bool   | Fermé                    |
| displayOrder   | int    | Ordre d'affichage        |

---

## Base de Données

### Schéma des Relations

```
User (1) ──────► (N) ContactMessage
Category (1) ──► (N) Dish
Dish (N) ──────► (1) Category
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
```

---

## Sécurité

### Configuration

- **Hashage des mots de passe** : Bcrypt
- **Protection CSRF** : Activée sur tous les formulaires
- **Sessions** : Natives PHP avec stockage fichiers
- **Rôles** : ROLE_ADMIN pour l'administration

### Accès

| Route        | Rôle requis   |
| ------------ | ------------- |
| /            | PUBLIC_ACCESS |
| /contact     | PUBLIC_ACCESS |
| /recrutement | PUBLIC_ACCESS |
| /login       | PUBLIC_ACCESS |
| /admin/\*    | ROLE_ADMIN    |

### Validations

Les formulaires utilisent des validations Regex :

- **Nom/Prénom** : `^[a-zA-ZÀ-ÿ\s\-]+$` (lettres, espaces, tirets)
- **Téléphone** : Format français international
- **Email** : Validation Symfony intégrée
- **Longueurs** : Min/Max configurés par champ

---

## Dashboard Administrateur

### Fonctionnalités

1. **Dashboard** - Statistiques et messages récents
2. **Catégories** - CRUD complet
3. **Plats** - CRUD complet avec gestion images
4. **Galerie** - CRUD photos
5. **Messages** - Consultation et suppression
6. **Horaires** - CRUD horaires d'ouverture

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
git clone <repository-url> the-klassiker
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

### Administration

1. Se connecter sur `/login`
2. Accéder au dashboard `/admin/`
3. Gérer les catégories, plats, photos, messages

### Modification du Menu

1. aller dans `/admin/category/` pour créer des catégories
2. Aller dans `/admin/dish/` pour ajouter des plats
3. Les plats s'afficheront automatiquement sur la page d'accueil

### Gestion des Images

- Les images de plats vont dans `public/uploads/`
- Les images du site sont dans `assets/images/`

---

## Bonnes Pratiques

### Développement

- Toujours utiliser les migrations pour modifier le schéma
- Valider les données avec les contraintes Symfony
- Utiliser les fixtures pour les données de test

### Production

- Désactiver le debug (`APP_DEBUG=0`)
- Configurer un email professionnel pour les notifications
- Sauvegarder régulièrement la base de données
- Utiliser HTTPS

### Sécurité

- Changer le secret (`APP_SECRET`) en production
- Utiliser des mots de passe forts
- Limiter les accès admin
- Mettre à jour régulièrement les dépendances

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
```

---

## Support

Pour toute question ou problème :

- Email : the.klassiker@gmail.com
- Consulter la documentation Symfony : https://symfony.com/doc/

---

## Licence

Propriétaire - The Klassiker © 2025
