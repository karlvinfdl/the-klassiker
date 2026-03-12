# The Klassiker — Site Restaurant Symfony 7

**Smash Burgers & Kebabs Berlinois — Évry-Courcouronnes (91000)**

> Site web full-stack développé avec Symfony 7, Doctrine ORM, Twig et Webpack Encore.

---

## Démarrage rapide

```bash
# Installer les dépendances
composer install && npm install

# Configurer l'environnement
cp .env .env.local
# → renseigner DATABASE_URL, MAILER_DSN, APP_SECRET

# Initialiser la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load   # données de démo

# Compiler les assets
npm run build

# Lancer le serveur
php bin/console server:run
```

**Accès admin :** `admin@theklassiker.fr` / `Klassiker2025!`
> ⚠️ Changer le mot de passe après la première connexion.

---

## Stack Technique

| Catégorie       | Technologie                              |
|-----------------|------------------------------------------|
| Framework       | Symfony 7.0                              |
| PHP             | 8.2+                                     |
| Base de données | MySQL 8                                  |
| ORM             | Doctrine ORM 2.17                        |
| Templates       | Twig                                     |
| CSS             | CSS3 custom + Bootstrap 5.3 (partiel)   |
| JavaScript      | Vanilla JS (ES6+)                        |
| Polices         | Google Fonts — Montserrat                |
| Icônes          | Font Awesome 6.5                         |
| Build tools     | Webpack Encore + npm                     |
| Email           | Symfony Mailer (SMTP)                    |
| Hébergement     | o2switch (PHP 8.3, MySQL 8, Linux)       |

---

## Structure du Projet

```
the-klassiker/
├── assets/
│   ├── images/               # 29 images WebP (galerie + produits)
│   ├── script/
│   │   └── app.js            # JS principal (galerie, menu, animations)
│   └── styles/
│       └── app.css           # CSS principal (design complet responsive)
├── config/
│   ├── packages/             # doctrine.yaml, security.yaml, mailer.yaml…
│   ├── routes.yaml
│   └── services.yaml
├── migrations/               # 3 migrations Doctrine
├── public/
│   ├── build/                # Assets compilés (Webpack)
│   ├── uploads/              # Images uploadées via admin
│   ├── .htaccess             # Routing Apache
│   └── index.php
├── src/
│   ├── Command/              # CreateAdminCommand
│   ├── Controller/
│   │   ├── Admin/            # DashboardController, CategoryController,
│   │   │                     # DishController, PhotoController,
│   │   │                     # MessageController, HoursController, UserController
│   │   ├── MainController.php
│   │   ├── OrderController.php
│   │   ├── ContactController.php
│   │   ├── SecurityController.php
│   │   ├── RegistrationController.php
│   │   └── UserController.php
│   ├── DataFixtures/
│   │   └── AppFixtures.php
│   ├── Entity/               # User, Category, Dish, Order, OrderItem,
│   │                         # GalleryPhoto, ContactMessage, OpeningHours
│   ├── Form/                 # ContactType, OrderType, RegistrationFormType…
│   ├── Repository/
│   ├── Security/
│   │   └── UserAuthenticator.php
│   └── Service/
│       └── EmailService.php
└── templates/
    ├── base.html.twig        # Template maître (header, footer, SEO)
    ├── admin/                # Dashboard + CRUD templates
    ├── email/                # 5 templates d'email
    ├── main/                 # index, contact, recrutement
    ├── order/                # index, panier, checkout, confirmation
    ├── security/             # login
    └── user/                 # dashboard, orders, order_show, profile
```

---

## Entités & Relations

```
User (1) ──────────► (N) Order
Category (1) ──────► (N) Dish
Order (1) ──────────► (N) OrderItem
OrderItem (N) ──────► (1) Dish
```

| Entité           | Champs principaux                                                                                  |
|------------------|----------------------------------------------------------------------------------------------------|
| `User`           | email, password (bcrypt), firstName, roles (JSON), createdAt                                       |
| `Category`       | name, slug (unique), description, displayOrder, isActive                                           |
| `Dish`           | name, description, price (decimal), image, displayOrder, isActive, isFeatured, category            |
| `Order`          | status, type (pickup/delivery), customerName, customerPhone, customerEmail, totalAmount            |
| `OrderItem`      | dish, dishName, unitPrice, quantity, specialInstructions                                           |
| `GalleryPhoto`   | filename, altText, displayOrder, isActive, createdAt                                               |
| `ContactMessage` | firstName, lastName, email, phone, subject, message, isRead, createdAt                             |
| `OpeningHours`   | dayName, dayOfWeek (0–6), morningOpen/Close, afternoonOpen/Close, isClosed                        |

**Statuts des commandes :** `pending` → `confirmed` → `preparing` → `ready` → `completed` / `cancelled`

---

## Routes

### Publiques

| URL                              | Description                      |
|----------------------------------|----------------------------------|
| `/`                              | Page d'accueil                   |
| `/contact`                       | Formulaire de contact            |
| `/recrutement`                   | Page recrutement                 |
| `/commande`                      | Carte & commande                 |
| `/commande/panier`               | Panier                           |
| `/commande/checkout`             | Validation commande              |
| `/commande/confirmation/{id}`    | Confirmation                     |
| `/login`                         | Connexion                        |
| `/inscription`                   | Inscription                      |

### Espace client (`ROLE_USER`)

| URL                          | Description            |
|------------------------------|------------------------|
| `/mon-compte/`               | Tableau de bord        |
| `/mon-compte/commandes`      | Historique commandes   |
| `/mon-compte/commandes/{id}` | Détail commande        |
| `/mon-compte/profil`         | Mon profil             |

### Administration (`ROLE_ADMIN`)

| URL                  | Description                     |
|----------------------|---------------------------------|
| `/admin/`            | Dashboard (stats + messages)    |
| `/admin/category/`   | CRUD Catégories                 |
| `/admin/dish/`       | CRUD Plats                      |
| `/admin/photo/`      | CRUD Galerie                    |
| `/admin/message/`    | Consultation messages contact   |
| `/admin/hours/`      | CRUD Horaires                   |
| `/admin/user/`       | Gestion utilisateurs            |

---

## Fonctionnalités

### Système de commande
- Panier en session (`order_cart`)
- Deux modes : Click & Collect / Livraison à domicile
- Suivi de statut : `pending` → `completed`
- Instructions spéciales par article
- Emails automatiques (confirmation client + alerte admin)

### Galerie photo
- 19 images WebP (`galerie-11` à `galerie-29`)
- Navigation par vignettes, flèches, clavier (**← →**)
- Swipe tactile (mobile)
- Auto-défilement toutes les 5 secondes
- Lightbox plein écran avec prev/next et compteur "X / 19"

### Authentification
- Inscription avec mot de passe fort (min. 8 car., maj., chiffre, spécial)
- Connexion par email (bcrypt)
- Rôles : `ROLE_USER`, `ROLE_ADMIN`
- Protection CSRF sur tous les formulaires

### SEO & Référencement
- `<meta name="description">` surchargeable par page via `{% block meta_description %}`
- **Open Graph** : `og:title`, `og:description`, `og:image`, `og:url`, `og:locale`
- **Twitter Card** : `summary_large_image`
- **URL canonique** automatique
- **JSON-LD** `Restaurant` (Schema.org) : adresse, horaires, réseaux sociaux, menu

### Emails (via `EmailService`)

| Déclencheur           | Destinataire | Template                              |
|-----------------------|--------------|---------------------------------------|
| Contact soumis        | Client       | `contact_confirmation.html.twig`      |
| Contact soumis        | Admin        | `admin_notification.html.twig`        |
| Commande passée       | Client       | `order_confirmation.html.twig`        |
| Commande passée       | Admin        | `new_order_admin.html.twig`           |
| Inscription           | Nouveau user | `registration_confirmation.html.twig` |

---

## Sécurité

| Mécanisme              | Configuration                                               |
|------------------------|-------------------------------------------------------------|
| Hachage mot de passe   | bcrypt (Symfony Password Hasher)                           |
| Authentification       | Passport-based (UserAuthenticator custom)                  |
| Protection CSRF        | Activée sur tous les formulaires Symfony                   |
| Routes admin           | `access_control` → `ROLE_ADMIN` requis                     |
| Liens externes         | `rel="noopener noreferrer"` systématique                   |
| Validation téléphone   | Regex française : `^(?:(?:\+\|00)33\|0)\s*[1-9]…`         |

---

## Commandes Utiles

```bash
# Assets
npm run watch                                   # Watch (dev)
npm run build                                   # Build production

# Cache
php bin/console cache:clear
php bin/console cache:warmup

# Base de données
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
php bin/console doctrine:schema:validate

# Debug
php bin/console debug:router
php bin/console debug:container

# Utilitaires
php bin/console app:create-admin                # Créer un admin
```

---

## Déploiement (o2switch)

```bash
# 1. Variables d'environnement
APP_ENV=prod
APP_DEBUG=0

# 2. Installer sans dépendances dev
composer install --no-dev --optimize-autoloader

# 3. Build assets
npm run build

# 4. Migrer
php bin/console doctrine:migrations:migrate --no-interaction

# 5. Chaud du cache
php bin/console cache:warmup

# 6. Permissions
chmod -R 755 var/ public/
```

Pointer le virtualhost vers le dossier `public/`.
Le fichier `public/.htaccess` est déjà configuré pour Symfony.

---

## Données de Démonstration (Fixtures)

- 1 admin : `admin@theklassiker.fr` / `Klassiker2025!`
- 8 catégories : Smash Burgers, Chicken, Kebabs Berlinois, Box et Assiettes, Salades, Sides, Desserts, Boissons
- Plusieurs plats par catégorie avec prix et descriptions
- Horaires : Lun–Jeu 11h–23h · Ven–Dim 11h–00h
- 19 photos de galerie

---

## Améliorations (mars 2026)

- **SEO** : Open Graph, Twitter Card, JSON-LD, canonical URL, meta description par page
- **Galerie** : bug d'index corrigé, navigation clavier ← →, swipe mobile, compteur, lightbox prev/next
- **Style** : section-kicker et section-subtitle lisibles sur fond beige, image galerie agrandie (460px), flèches avec hover jaune, vignettes avec opacité, hover réseaux sociaux
- **Accessibilité** : `role="dialog"`, `aria-modal`, `aria-label`, `rel="noopener noreferrer"`

---

## Licence

Propriétaire — The Klassiker © 2026
