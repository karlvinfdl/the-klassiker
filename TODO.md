# The Klassiker - Symfony 7 - Checklist de Développement

## ✅ Tâches Accomplies

### Configuration Projet

- [x] Projet Symfony 7 créé
- [x] Composer.json configuré avec toutes les dépendances
- [x] Configuration sécurité (bcrypt, roles, firewall)
- [x] Configuration Webpack Encore pour assets
- [x] Configuration sessions et CSRF
- [x] Documentation README.md créée

### Entités Doctrine

- [x] User (administrateur)
- [x] Category (catégories de plats)
- [x] Dish (plats/menu items)
- [x] GalleryPhoto (photos galerie)
- [x] ContactMessage (messages contact)
- [x] OpeningHours (horaires d'ouverture) - CORRIGÉ: propriété dayName ajoutée

### Base de données

- [x] Migration initiale créée
- [x] Contraintes de clés étrangères
- [x] Index sur les champs fréquement interrogés
- [x] Colonne day_name dans opening_hours

### Sécurité

- [x] Hashage mots de passe (bcrypt)
- [x] Protection CSRF sur tous les formulaires
- [x] Firewall configuré
- [x] Accès admin protégé
- [x] Validations avec Regex
- [x] UserAuthenticator corrigé avec import Passport

### Controllers

- [x] MainController (accueil, recrutement)
- [x] ContactController (formulaire contact)
- [x] SecurityController (login/logout)
- [x] Admin DashboardController
- [x] Admin CategoryController (CRUD)
- [x] Admin DishController (CRUD avec upload)
- [x] Admin PhotoController (CRUD)
- [x] Admin MessageController (CRUD)
- [x] Admin HoursController (CRUD)

### Templates

- [x] base.html.twig (template principal)
- [x] main/index.html.twig (page d'accueil)
- [x] main/contact.html.twig (contact)
- [x] main/recrutement.html.twig (recrutement)
- [x] main/gallery.html.twig (galerie)
- [x] security/login.html.twig (connexion)
- [x] admin/base.html.twig (layout admin)
- [x] admin/dashboard.html.twig
- [x] CRUD admin (category, dish, photo, message, hours)

### Services

- [x] EmailService (notifications, confirmations)
- [x] Configuration services.yaml - CORRIGÉ

### Data Fixtures

- [x] Admin user créé
- [x] Catégories par défaut
- [x] Plats par défaut
- [x] Horaires d'ouverture
- [x] Photos galerie

### Assets

- [x] CSS intégré via Webpack
- [x] JS intégré via Webpack
- [x] Images optimisées

### Corrections effectuées

- [x] OpeningHours: propriété dayName ajoutée à l'entité et migration
- [x] UserAuthenticator: import Passport ajouté
- [x] framework.yaml: sessions activées
- [x] security.yaml: configuration nettoyée
- [x] doctrine.yaml: option enable_lazy_loading_objects supprimée
- [x] EmailService: arguments du constructeur corrigés

---

## 🔄 Tâches à Faire / Vérifications

### Commandes à exécuter (dev)

```bash
# Configurer .env.local avec DATABASE_URL et MAILER_DSN

# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Charger les données fixtures
php bin/console doctrine:fixtures:load

# Vider le cache
php bin/console cache:clear

# Démarrer le serveur dev
php bin/console server:run
```

### Configuration à vérifier avant production

- [x] .env configuré (DATABASE_URL, MAILER_DSN, etc.) - À configurer
- [ ] Configuration mailer (SMTP ou sendmail)
- [x] APP_SECRET généré
- [ ] Debug désactivé en prod

### Déploiement o2switch

- [x] Configuration .htaccess existante
- [x] Dossier public/ prêt comme racine web
- [ ] Variables d'environnement production
- [ ] Permissions sur dossiers (var/, public/)

---

## 📋 Commandes Utilitaires

```bash
# Créer une entité
php bin/console make:entity

# Créer un controller
php bin/console make:controller

# Créer un formulaire
php bin/console make:form

# Créer une migration après modification entité
php bin/console make:migration

# Créer un utilisateur admin
php bin/console app:create-admin-user

# Vérifier la configuration
php bin/console debug:config framework
```

---

## Identifiants Admin par défaut

```
Email : admin@theklassiker.fr
Mot de passe : Klassiker2025!
```

⚠️ À changer immédiatement après la première connexion !
