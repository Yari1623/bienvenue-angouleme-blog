# 05 – Sécurité

La sécurité est traitée à plusieurs niveaux : authentification, protection des formulaires,
sécurisation des données et configuration serveur.

---

## 1. Hashage des mots de passe

**Mécanisme :** `password_hash($password, PASSWORD_BCRYPT)`

Le mot de passe n'est **jamais stocké en clair**. PHP utilise l'algorithme bcrypt avec un
facteur de coût par défaut (10 rounds), ce qui rend les attaques par force brute extrêmement
coûteuses en temps de calcul.

La vérification se fait avec `password_verify($input, $hash)` — PHP compare le hash stocké
avec le hash calculé depuis l'input sans jamais manipuler la valeur en clair.

```php
// Inscription
$hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

// Connexion
if (!password_verify($_POST['password'], $user['password'])) {
    Flash::error('Identifiants incorrects.');
}
```

---

## 2. Protection contre les injections SQL (PDO + requêtes préparées)

**Toutes les requêtes SQL** du projet utilisent PDO avec des **requêtes préparées** et des
paramètres liés (`:param`). Aucune concaténation de variables dans les requêtes SQL.

```php
// CORRECT — requête préparée avec paramètre lié
$stmt = $this->pdo->prepare("SELECT * FROM posts WHERE slug = :slug");
$stmt->execute(['slug' => $slug]);

// INCORRECT — jamais utilisé dans ce projet
$stmt = $this->pdo->query("SELECT * FROM posts WHERE slug = '$slug'");
```

Les paramètres liés sont automatiquement échappés par le driver PDO, ce qui élimine
complètement le risque d'injection SQL quelle que soit la valeur passée.

---

## 3. Protection CSRF (Cross-Site Request Forgery)

**Mécanisme :** Token aléatoire en session, vérifié sur chaque action POST.

```php
// Génération (dans la vue, avant chaque formulaire POST)
<input type="hidden" name="_csrf" value="<?= Csrf::generate() ?>">

// Validation (dans chaque contrôleur traitant un POST)
if (!Csrf::validate($_POST['_csrf'] ?? null)) {
    http_response_code(403); exit('Requête invalide');
}
```

**Détails techniques :**
- Token généré avec `bin2hex(random_bytes(32))` → 64 caractères hexadécimaux aléatoires
- Stocké en session `$_SESSION['csrf_token']`
- Comparaison avec `hash_equals()` → résistant aux attaques temporelles (timing attacks)
- **Rotation du token** après chaque validation → un token ne peut être rejoué

Toutes les routes POST sont protégées : création/modification/suppression d'articles,
commentaires, likes, intérêts événements, gestion des utilisateurs, changement de rôle.

---

## 4. Échappement des sorties HTML (XSS)

**Mécanisme :** `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` sur toutes les données
affichées provenant de l'utilisateur.

```php
// Dans les vues — toujours échapper les données utilisateur
<?= htmlspecialchars($user['username']) ?>
<?= htmlspecialchars($post['title']) ?>
```

Cela transforme les caractères dangereux (`<`, `>`, `"`, `'`, `&`) en entités HTML,
empêchant l'injection de code JavaScript (XSS) dans les pages.

---

## 5. Gestion des rôles et protection des routes

**Trois niveaux d'accès** sont implémentés :

|    Niveau    |       Description       |           Routes concernées           |
|--------------|-------------------------|---------------------------------------| 
| **Visiteur** | Non connecté            | Pages publiques uniquement            |
| **Membre**   | Connecté, rôle `member` | + Profil, compte, commentaires, likes |
| **Admin**    | Connecté, rôle `admin`  | + Toutes les routes `/admin/*`        |

La protection est appliquée directement dans le `Router` :
```php
// Dans config/routes.php
$router->get('admin', 'AdminController@index', auth: true, role: 'admin');
$router->post('compte/update-infos', 'AccountController@updateInfos', auth: true);
```

Le `Router::dispatch()` vérifie les droits **avant** d'instancier le contrôleur.
La validation du rôle côté contrôleur pour `updateRole()` utilise une whitelist :
```php
if (!in_array($role, ['admin', 'member'], true)) {
    Flash::error('Rôle invalide.'); exit;
}
```

---

## 6. Régénération de l'ID de session à la connexion

À chaque connexion réussie, l'ID de session est régénéré pour prévenir les attaques
de **session fixation** :
```php
session_regenerate_id(true); // true = supprime l'ancienne session
$_SESSION['user'] = $user;
```

---

## 7. Configuration serveur : .htaccess

Le fichier `public/.htaccess` ajoute deux protections serveur :

```apache
# Désactive le listing des répertoires
Options -Indexes

# Redirige tout vers index.php sauf fichiers/dossiers existants
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```

`Options -Indexes` empêche qu'un visiteur liste le contenu d'un dossier
si aucun `index.php` n'est présent (exposition d'arborescence).

---

## 8. Variables d'environnement

Les informations sensibles (identifiants BDD, clés secrètes) sont stockées dans un
fichier `.env` à la racine du projet, **jamais versionné** (ajouté au `.gitignore`) :

```
DB_HOST=127.0.0.1
DB_NAME=bienvenue_blog
DB_USER=root
DB_PASS=motdepasse
```

En production, ces variables peuvent être définies directement au niveau du serveur
(variables d'environnement Apache/cPanel) sans fichier `.env`.

---

## 9. Validation côté serveur

Toutes les données de formulaire sont validées côté serveur dans les contrôleurs,
indépendamment de la validation HTML5 côté client :
- Vérification des champs obligatoires (trim + empty)
- Validation du format email (`filter_var($email, FILTER_VALIDATE_EMAIL)`)
- Vérification de l'unicité (email, username) avant insertion
- Validation des valeurs d'enum (rôle whitelist, statut whitelist)

---

## Récapitulatif des vecteurs d'attaque couverts

|           Attaque          |          Mécanisme de protection          |
|----------------------------|-------------------------------------------|
| Injection SQL              | PDO + requêtes préparées                  |
| XSS (Cross-Site Scripting) | htmlspecialchars() sur toutes les sorties |
| CSRF                       | Token CSRF sur tous les formulaires POST  |
| Brute force mot de passe   | bcrypt (coût de calcul élevé)             |
| Session fixation           | session_regenerate_id() à la connexion    |
| Directory listing          | Options -Indexes dans .htaccess           |
| Accès non autorisé         | Vérification auth + rôle dans le Router   |
| Timing attack              | hash_equals() pour comparaison CSRF       |
| Injection de rôle          | Whitelist côté serveur                    |

