# 03 – Architecture MVC

## Vue d'ensemble

Le projet repose sur une architecture **MVC (Modèle – Vue – Contrôleur) personnalisée**,
entièrement développée sans framework. L'organisation du code suit le principe de
**séparation des responsabilités** : chaque couche a un rôle unique et délimité.

```
bienvenue-angouleme-blog/
├── public/           ← Seul dossier accessible depuis le web
│   └── index.php     ← Front Controller unique
├── app/
│   ├── core/         ← Classes fondamentales du framework maison
│   ├── controllers/  ← 18 contrôleurs
│   ├── models/       ← 6 modèles
│   └── helpers/      ← Classes utilitaires (Slug)
├── config/
│   ├── routes.php    ← Déclaration de toutes les routes
│   └── database.php  ← Configuration BDD (lecture depuis .env)
├── views/            ← ~30 fichiers de vues PHP
└── .env              ← Variables d'environnement (non versionné)
```

---

## 1. Le Front Controller : public/index.php

**Principe :** Un seul point d'entrée pour toutes les requêtes HTTP.

Le fichier `.htaccess` redirige toutes les URL vers `public/index.php` via `mod_rewrite`.
Ce fichier :
1. Définit les constantes globales (`BASE_URL`, `BASE_PATH`)
2. Enregistre l'autoloader
3. Charge les variables d'environnement via `Env`
4. Initialise la session PHP
5. Instancie le `Router`
6. Charge `config/routes.php` qui déclare toutes les routes
7. Extrait le chemin URI et appelle `$router->dispatch($uri)`

---

## 2. Le Core : app/core/

Le dossier `core/` contient les classes fondamentales réutilisées par toute l'application.

### Autoloader.php
Implémente le chargement automatique des classes PHP selon la convention PSR-4 :
- Espace de noms `App\Controllers\PostController` → `app/controllers/PostController.php`
- Espace de noms `App\Models\User` → `app/models/User.php`
- Espace de noms `App\Core\Router` → `app/core/Router.php`
Enregistré via `spl_autoload_register()`.

### Router.php
Le routeur gère la correspondance entre les URLs et les contrôleurs :
- Méthodes `get()` et `post()` pour déclarer les routes
- Support des **segments dynamiques** : `admin/posts/{id}/edit` → groupe de capture regex `([^/]+)`
- Protection intégrée : paramètre `$auth` (connexion requise) et `$role` (rôle requis)
- Redirection automatique vers `/login` si non authentifié
- Réponse 403 si le rôle est insuffisant
- Dispatch vers `Controller@méthode` avec passage des paramètres dynamiques

### Database.php
Implémente le pattern **Singleton** pour la connexion PDO :
- Une seule connexion partagée pour toute la requête HTTP
- Lecture des paramètres depuis les variables d'environnement (`$_ENV`)
- Configuration : `ATTR_ERRMODE => ERRMODE_EXCEPTION`, `FETCH_ASSOC` par défaut
- Encodage UTF-8 garanti via `charset=utf8mb4`

### Model.php (classe de base)
Classe abstraite dont héritent tous les modèles :
- Injecte l'instance PDO via `Database::getPDO()`
- Fournit `$this->pdo` à tous les modèles enfants

### Controller.php (classe de base)
Classe abstraite dont héritent tous les contrôleurs :
- Méthode `view(string $view, array $data = [])` : extrait les données avec `extract()`,
  détermine le chemin de la vue et l'inclut dans le layout `views/layouts/main.php`

### Auth.php
Gestion de l'authentification basée sur la session PHP :
- `Auth::check()` : vérifie si l'utilisateur est connecté (`$_SESSION['user']`)
- `Auth::user()` : retourne le tableau des données utilisateur
- `Auth::id()` : retourne l'identifiant de l'utilisateur courant
- `Auth::isAdmin()` : vérifie le rôle `admin`
- `Auth::login(array $user)` : stocke l'utilisateur en session avec régénération d'ID
- `Auth::logout()` : détruit la session complètement

### Csrf.php
Protection contre les attaques **Cross-Site Request Forgery** :
- `Csrf::generate()` : génère un token aléatoire (`bin2hex(random_bytes(32))`) et le stocke en session
- `Csrf::validate(string $token)` : compare le token soumis avec celui en session avec `hash_equals()`
  (résistant aux attaques temporelles)
- **Rotation du token** après chaque validation pour éviter les replays

### Flash.php
Système de messages flash (persistance d'un message entre deux requêtes HTTP) :
- `Flash::success(string $msg)` : enregistre un message de succès en session
- `Flash::error(string $msg)` : enregistre un message d'erreur en session
- `Flash::get()` : récupère et **vide** les messages (consommation unique)
- Affiché automatiquement dans `views/layouts/main.php` après chaque redirection

### Env.php
Chargement du fichier `.env` :
- Lecture ligne par ligne du fichier `.env` à la racine
- Peuplement de `$_ENV` et `putenv()` pour chaque variable
- Ignoré si le fichier n'existe pas (production avec variables serveur)

---

## 3. Les Modèles : app/models/

Les modèles héritent de `Model` et encapsulent toutes les interactions avec la base de données.
**Aucune requête SQL n'apparaît dans les contrôleurs ou les vues.**

|     Modèle     |                      Responsabilités principales                      |
|----------------|-----------------------------------------------------------------------|
| `User.php`     | CRUD, authentification, vérification unicité email/username, stats    |
| `Post.php`     | CRUD articles, sections, likes, vues, recherche full-text, pagination |
| `Category.php` | CRUD catégories avec comptage d'articles (`post_count`)               |
| `Place.php`    | CRUD lieux                                                            |
| `Comment.php`  | CRUD commentaires, modération, commentaires approuvés par article     |
| `Event.php`    | CRUD événements, gestion des intérêts utilisateurs                    |

---

## 4. Les Contrôleurs : app/controllers/

18 contrôleurs couvrent l'ensemble des fonctionnalités :

|       Contrôleur       |                           Rôle                           |
|------------------------|----------------------------------------------------------|
| `HomeController`       | Page d'accueil                                           |
| `BlogController`       | Liste des articles avec filtres et pagination            |
| `PostController`       | Affichage article, commentaire, like + CRUD admin        |
| `CategoryController`   | Page par catégorie publique                              |
| `CategoriesController` | Page liste des catégories et lieux                       |
| `EventController`      | Agenda public + CRUD admin + toggle intérêt              |
| `AdminController`      | Dashboard avec statistiques et graphiques                |
| `CommentController`    | Modération admin des commentaires                        |
| `UserController`       | Gestion admin des utilisateurs                           |
| `ProfileController`    | Page profil membre                                       |
| `AccountController`    | Modification compte membre                               |
| `LoginController`      | Connexion / déconnexion                                  |
| `RegisterController`   | Inscription                                              |
| `ContactController`    | Formulaire de contact                                    |
| `AboutController`      | Page À propos                                            |
| `HistoireController`   | Page Histoire d'Angoulême                                |
| `LegalController`      | Pages légales (mentions, RGPD, cookies, confidentialité) |
| `ErrorController`      | Page 404                                                 |

---

## 5. Les Vues : views/

Les vues sont des fichiers PHP purs. Elles ne contiennent **aucune requête SQL**.
Toutes les vues sont enveloppées par le layout `views/layouts/main.php`.

**Structure :**
```
views/
├── layouts/main.php     ← Layout commun (header, footer, CSS, JS)
├── partials/
│   └── post-card.php    ← Composant réutilisable carte article
├── home/index.php
├── blog/index.php
├── posts/show.php
├── admin/
│   ├── index.php        ← Dashboard
│   ├── posts/           ← CRUD articles
│   ├── comments/
│   ├── users/
│   ├── categories/
│   └── events/
├── profil/
├── auth/
├── legal/
└── errors/404.php
```

---

## 6. Le Routeur en détail

Exemple de déclaration de routes dans `config/routes.php` :

```php
// Route publique simple
$router->get('blog', 'BlogController@index');

// Route avec paramètre dynamique
$router->get('article/{slug}', 'PostController@show');

// Route protégée (connexion requise)
$router->get('profil', 'ProfileController@index', auth: true);

// Route protégée par rôle admin
$router->get('admin', 'AdminController@index', auth: true, role: 'admin');

// Route POST avec CSRF dans le contrôleur
$router->post('article/{slug}/comment', 'PostController@comment', auth: true);
```

---

## 7. Flux d'une requête HTTP

```
Navigateur : GET /blog?categorie=culture
     │
     ▼
.htaccess → redirige vers public/index.php
     │
     ▼
index.php → Autoloader, Env, Session, Router
     │
     ▼
Router::dispatch('blog') → BlogController@index
     │
     ▼
BlogController → appelle Post::search(catSlug='culture', ...)
     │
     ▼
Post::search() → requête PDO préparée → retourne array
     │
     ▼
BlogController → $this->view('blog/index', ['posts' => $posts, ...])
     │
     ▼
Controller::view() → extract($data), require layouts/main.php
     │
     ▼
main.php → require views/blog/index.php → HTML envoyé au navigateur
```

