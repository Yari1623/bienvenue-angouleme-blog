# 04 – Base de données

## Système de gestion

**MySQL 9.1.0** avec moteur **InnoDB** pour toutes les tables.
Encodage : **utf8mb4_unicode_ci** (support complet Unicode et emojis).
Interface d'administration : **phpMyAdmin 5.2.1**.

---

## Tables et structure

La base `bienvenue_blog` contient **10 tables** :

|       Table       | Colonnes |                    Rôle                    |
|-------------------|----------|--------------------------------------------|
| `users`           | 10       | Comptes membres et administrateurs         |
| `categories`      | 4        | Thèmes des articles                        |
| `places`          | 5        | Zones géographiques                        |
| `posts`           | 13       | Articles du blog                           |
| `post_sections`   | 6        | Blocs de contenu (texte, image, vidéo…)    |
| `post_categories` | 2        | Liaison many-to-many articles ↔ catégories |
| `comments`        | 6        | Commentaires des membres                   |
| `likes`           | 4        | J'aimes sur les articles                   |
| `post_views`      | 5        | Historique de consultation                 |
| `events`          | 7        | Événements de l'agenda                     |
| `event_interests` | 4        | Membres intéressés par un événement        |

---

## Relations et cardinalités

### Relation 1:N — users → posts
Un utilisateur peut rédiger plusieurs articles. Chaque article a obligatoirement un auteur.
- Clé étrangère : `posts.author_id → users.id`
- Contrainte : `ON DELETE CASCADE` (suppression de l'utilisateur → suppression de ses articles)

### Relation 1:N — categories → posts
Une catégorie regroupe plusieurs articles. Un article peut ne pas avoir de catégorie.
- Clé étrangère : `posts.category_id → categories.id`
- Contrainte : `ON DELETE SET NULL` (suppression de la catégorie → les articles restent)

### Relation 1:N — places → posts
Un lieu géographique peut concerner plusieurs articles.
- Clé étrangère : `posts.place_id → places.id`
- Contrainte : `ON DELETE SET NULL`

### Relation 1:N — posts → post_sections
Un article est composé de plusieurs blocs de contenu ordonnés.
- Clé étrangère : `post_sections.post_id → posts.id`
- Contrainte : `ON DELETE CASCADE`
- Types de blocs : `text`, `title`, `image`, `video`, `quote`, `gallery`

### Relation N:M — posts ↔ categories (via post_categories)
Un article peut appartenir à plusieurs catégories.
- Clés : `post_id → posts.id` + `category_id → categories.id`
- Clé primaire composite `(post_id, category_id)` garantit l'unicité

### Relation 1:N — users & posts → comments
Un commentaire appartient à un auteur et à un article.
- Clés : `comments.user_id → users.id` + `comments.post_id → posts.id`
- Statut ENUM : `pending` (défaut), `approved`, `rejected`
- Modération obligatoire avant publication

### Relation N:M — users & posts → likes
Un utilisateur peut liker plusieurs articles.
- Contrainte `UNIQUE KEY (post_id, user_id)` : un seul like par utilisateur par article
- Implémente le toggle like/unlike côté application

### Relation 1:N — posts & users → post_views
Historique de lecture des articles.
- `user_id` nullable (visiteurs anonymes identifiés par IP)
- `ON DELETE SET NULL` sur user_id (les statistiques persistent après suppression du compte)

### Relation N:M — events ↔ users (via event_interests)
Un membre peut suivre plusieurs événements.
- Contrainte `UNIQUE KEY (user_id, event_id)` : un seul intérêt par membre par événement

---

## Choix de conception

### Pourquoi CASCADE ou SET NULL ?
- **CASCADE** : quand les données enfants n'ont pas de sens sans le parent
  (sections sans article, commentaires sans article)
- **SET NULL** : quand les données enfants conservent leur valeur indépendamment
  (un article reste lisible sans catégorie)

### Pourquoi les contraintes UNIQUE sur likes et event_interests ?
Double protection :
1. **Intégrité technique** : MySQL rejette les doublons même en cas de bug applicatif
2. **Performance** : l'index UNIQUE accélère les lookups `hasLiked()` et `isInterested()`

### Pourquoi l'éditeur par blocs (post_sections) ?
Plutôt qu'un champ HTML monolithique, le contenu est décomposé en blocs typés et ordonnés.
Avantages : flexibilité éditoriale, maintenabilité du code, extensibilité (ajout d'un nouveau
type de bloc sans toucher au schéma).

### Pourquoi stocker les vues anonymes ?
La majorité des lecteurs d'un blog ne sont pas connectés. Ignorer leurs vues fausserait
les statistiques. L'IP permet une déduplication minimale conforme aux principes RGPD
(minimisation des données).

---

## Indexation

Les colonnes fréquemment interrogées sont indexées pour les performances :

|     Table    |                Index                |            Usage            |
|--------------|-------------------------------------|-----------------------------|
| `posts`      | `idx_posts_slug`                    | Affichage article par slug  |
| `posts`      | `idx_posts_status`                  | Filtrage publié/brouillon   |
| `posts`      | `idx_posts_created`                 | Tri chronologique           |
| `categories` | `idx_categories_slug`               | Navigation par catégorie    |
| `events`     | `idx_events_date`                   | Tri des événements par date |
| `users`      | `idx_users_email`, `idx_users_role` | Authentification, stats     |

---

## Diagramme

Le diagramme relationnel complet est disponible dans le fichier `bdd-relations.docx`
du dossier professionnel, ainsi que via dbdiagram.io en important `database/schema.sql`.

