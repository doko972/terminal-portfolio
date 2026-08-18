# 🖥️ Portfolio Terminal - Développeur Web & Technicien Systèmes

Un portfolio interactif au design Terminal/Matrix, développé avec Laravel 10, mettant en avant mes compétences en développement web et en systèmes & réseaux.

![Version](https://img.shields.io/badge/version-1.0.0-green.svg)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)

## 🎯 Fonctionnalités

### 🌐 Site Public
- ✅ **Animation Matrix** - Effet de code défilant en arrière-plan
- ✅ **Boot Screen** - Écran de démarrage sécurisé style système Linux
- ✅ **Portfolio dynamique** - Affichage des projets avec filtres par technologies
- ✅ **Modal de détails** - Vue détaillée de chaque projet
- ✅ **Design responsive** - Compatible mobile, tablette et desktop
- ✅ **Menu burger animé** - Navigation fluide sur mobile

### 🔐 Espace Administration
- ✅ **Authentification sécurisée** - Laravel Breeze avec vérification email
- ✅ **CRUD Projets** - Gestion complète des projets avec upload d'images
- ✅ **Dashboard** - Statistiques et actions rapides
- ✅ **Gestion du profil** - Modification des informations personnelles
- ✅ **Navigation Terminal** - Interface admin cohérente avec le thème

## 🛠️ Technologies Utilisées

### Backend
- **Laravel 10** - Framework PHP
- **MySQL** - Base de données
- **Laravel Breeze** - Authentification

### Frontend
- **Sass** - Préprocesseur CSS
- **JavaScript Vanilla** - Interactions dynamiques
- **Vite** - Build tool moderne
- **Alpine.js** - Framework JavaScript léger

### Design
- **Roboto Mono** - Police monospace
- **Animation Matrix** - Canvas HTML5
- **Theme Terminal** - Vert néon (#0ee027) sur fond noir

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **npm** >= 9.x
- **MySQL** >= 8.0
- **Git**

## 🚀 Installation

### 1️⃣ Cloner le dépôt
```bash
git clone https://github.com/votre-username/portfolio-terminal.git
cd portfolio-terminal
```

### 2️⃣ Installer les dépendances PHP
```bash
composer install
```
### 3️⃣ Installer les dépendances Node.js
```bash
npm install
```
### 4️⃣ Configuration de l'environnement
# Copiez le fichier .env.example et configurez vos variables :

```bash
cp .env.example .env


APP_NAME="PortfolioTerminal"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio_terminal
DB_USERNAME=root
DB_PASSWORD=

# Configuration mail (optionnel)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

### 5️⃣ Générer la clé d'application
```bash
php artisan key:generate
```

### 6️⃣ Créer la base de données
```bash
Créez une base de données MySQL nommée portfolio_terminal (ou le nom défini dans .env)
```


### 7️⃣ Exécuter les migrations
```bash
php artisan migrate
```

### 8️⃣ Créer le lien symbolique pour le stockage
```bash
php artisan storage:link
```

### 9️⃣ Compiler les assets
# En développement (avec hot reload) :
```bash
npm run dev
```

# Pour la production :
```bash
npm run build
```

### 🔟 Lancer le serveur
```bash
php artisan serve
```

# Le site sera accessible sur : http://localhost:8000

### Créer un compte administrateur

L'inscription publique est désactivée : le portfolio n'a qu'un seul
administrateur. Le compte se crée en ligne de commande.

```bash
php artisan tinker
```
```php
App\Models\User::create([
    'name' => 'Votre nom',
    'email' => 'vous@example.com',
    'password' => Hash::make('un-mot-de-passe-solide'),
    'is_admin' => true,
]);
```

Connectez-vous ensuite sur `/se_logger`, puis l'administration est accessible
sur `/admin/projects` et `/admin/experiences`. Un compte sans `is_admin`
reçoit un 403 sur ces pages.

## 🚀 Déploiement

```bash
git pull
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan optimize          # config + routes + vues en cache
```

Variables requises dans le `.env` de production :

| Variable | Rôle |
|---|---|
| `RECAPTCHA_SITE_KEY` | Widget reCAPTCHA du formulaire de contact |
| `RECAPTCHA_SECRET_KEY` | Vérification serveur du captcha |
| `CONTACT_TO_ADDRESS` | Destinataire du formulaire (optionnel) |

`SESSION_DRIVER=database` requiert la table `sessions`, créée par les
migrations. Après tout changement de `.env`, relancer `php artisan optimize`.

```bash
Structure du Projet
portfolio-terminal/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       │   └── ProjectController.php      # CRUD Projets
│   │       ├── HomeController.php             # Page d'accueil
│   │       └── ProfileController.php          # Gestion profil
│   │
│   └── Models/
│       ├── Project.php                        # Model Projet
│       └── User.php                           # Model Utilisateur
│
├── database/
│   └── migrations/
│       └── xxxx_create_projects_table.php     # Table projets
│
├── public/
│   ├── images/                                # Images statiques (logo, etc.)
│   └── storage/                               # Lien symbolique → storage/app/public
│
├── resources/
│   ├── js/
│   │   ├── app.js                             # JS principal
│   │   └── portfolio.js                       # JS section portfolio
│   │
│   ├── sass/
│   │   ├── app.scss                           # Styles principaux
│   │   ├── _navigation.scss                   # Navigation admin
│   │   ├── _projects.scss                     # Gestion projets admin
│   │   └── _portfolio.scss                    # Section portfolio public
│   │
│   └── views/
│       ├── admin/
│       │   └── projects/
│       │       ├── index.blade.php            # Liste projets
│       │       ├── create.blade.php           # Créer projet
│       │       └── edit.blade.php             # Modifier projet
│       │
│       ├── auth/                              # Pages authentification
│       ├── layouts/
│       │   ├── app.blade.php                  # Layout admin
│       │   ├── terminal.blade.php             # Layout public
│       │   └── navigation.blade.php           # Navigation admin
│       │
│       ├── dashboard.blade.php                # Dashboard admin
│       ├── profile/                           # Gestion profil
│       └── welcome.blade.php                  # Page d'accueil publique
│
├── routes/
│   └── web.php                                # Routes de l'application
│
├── storage/
│   └── app/
│       └── public/
│           └── projects/                      # Images des projets
│
├── .env.example                               # Configuration exemple
├── composer.json                              # Dépendances PHP
├── package.json                               # Dépendances Node.js
├── vite.config.js                             # Configuration Vite
└── README.md                                  # Ce fichier

```

```
### Dépannage:
# Les images ne s'affichent pas
```bash
php artisan storage:link
php artisan cache:clear
```

# Erreur 500 après installation
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

# Les assets ne se chargent pas
```bash
npm run build
php artisan optimize
```
### Captures d'écran
# Page d'accueil avec animation Matrix
<img width="2215" height="1198" alt="Capture d'écran 2025-10-12 204254" src="https://github.com/user-attachments/assets/496880c6-92ce-4a3d-88bb-364c81d9ff0a" />

# Boot Screen sécurisé
<img width="1488" height="1082" alt="Capture d'écran 2025-10-12 204306" src="https://github.com/user-attachments/assets/3f6ff2d1-539e-4ef6-befe-dd915e78e86b" />

### Roadmap
Version 2.0 (À venir)

 Formulaire de contact avec notification email
 Timeline du parcours professionnel
 Section Certifications
 Upload et téléchargement de CV
 Terminal interactif avec commandes
 Mode sombre/clair
 Multilingue (FR/EN)
 Blog technique
 API REST pour les projets

### Licence
Ce projet est sous licence MIT.
### Auteur
Doko972

Email : doko972@gmail.com

### Remerciements

Laravel - Framework PHP
Tailwind CSS - Framework CSS
Matrix Rain Effect - Inspiration pour l'animation
Google Fonts - Police Roboto Mono
