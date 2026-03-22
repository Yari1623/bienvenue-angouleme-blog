# Préparation à l'oral DWWM

## Structure de l'oral

L'examen DWWM se déroule en deux parties :

| Partie | Durée | Contenu |
|---|---|---|
| Présentation du projet | 35 min | Exposé de la démarche et du projet |
| Entretien technique | 40 min | Questions du jury sur le projet et les compétences |

---

## 1. Présentation recommandée (35 minutes)

### Introduction — 3 min
- Se présenter, présenter la formation et l'organisme
- Annoncer le sujet : "Je vais vous présenter mon projet de titre professionnel : Bienvenue à Angoulême, un blog local développé en PHP MVC"
- Annoncer le plan

### Contexte et objectifs — 3 min
- Pourquoi ce projet ? Besoin identifié, public cible
- Objectifs pédagogiques couverts
- Technologies retenues et pourquoi

### Architecture technique — 7 min
- Expliquer le MVC personnalisé avec un schéma
- Montrer le flux d'une requête HTTP de bout en bout
- Présenter les classes Core (Router, Auth, Csrf, Flash)
- Insister sur l'absence de framework (choix délibéré)

### Base de données — 5 min
- Présenter le diagramme relationnel (dbdiagram.io)
- Expliquer 2-3 relations clés (users→posts, likes N:M, post_sections)
- Mentionner les choix CASCADE vs SET NULL

### Démonstration live — 10 min
Parcours utilisateur :
1. Page d'accueil → navigation blog → lecture d'un article
2. Connexion → like → commentaire → profil
3. Connexion admin → dashboard → créer un article → modérer un commentaire

### Sécurité — 3 min
- CSRF : montrer un token dans le code source d'un formulaire
- bcrypt : montrer un hash dans la BDD
- Requêtes préparées : montrer un exemple de code

### Responsive et UX — 2 min
- Montrer le site sur mobile (devtools Chrome)
- Expliquer les choix dark/light mode, police éditoriale

### Conclusion — 2 min
- Bilan des compétences acquises
- Évolutions envisagées (API REST, newsletter)
- Ouverture

---

## 2. Questions fréquentes du jury — Réponses préparées

### Architecture

**Q : Pourquoi avoir développé votre propre MVC plutôt qu'utiliser Laravel ?**

> Laravel est un excellent framework, mais il masque les mécanismes fondamentaux derrière
> de nombreuses abstractions. Dans le cadre de la certification DWWM, j'ai voulu démontrer
> que je comprends ces mécanismes en profondeur : comment fonctionne un routeur, comment
> implémenter l'authentification, comment structurer les couches. Écrire son propre MVC
> force à résoudre ces problèmes sans filet de sécurité. De plus, le projet n'a aucune
> dépendance externe — pas de Composer, pas de node_modules — ce qui le rend portable
> et simple à déployer.

**Q : Comment fonctionne votre Router ?**

> Le Router expose deux méthodes : `get()` et `post()` pour enregistrer des routes.
> Chaque route est compilée en expression régulière — les segments dynamiques `{id}` sont
> convertis en groupes de capture `([^/]+)`. Lors du dispatch, on parcourt les routes
> enregistrées pour la méthode HTTP courante et on cherche une correspondance regex.
> Si trouvée, on vérifie les droits (auth, rôle), on extrait les paramètres dynamiques,
> et on instancie le contrôleur pour appeler la méthode. Si aucune route ne correspond,
> on appelle ErrorController::notFound() et on retourne un HTTP 404.

**Q : Comment fonctionne l'autoloader ?**

> J'ai implémenté un autoloader PSR-4 enregistré avec `spl_autoload_register()`.
> Quand PHP rencontre une classe inconnue, il appelle l'autoloader avec le nom complet
> qualifié. L'autoloader convertit les séparateurs de namespace `\` en séparateurs de
> dossier `/`, applique le préfixe de chemin de base, et inclut le fichier PHP
> correspondant. Par exemple, `App\Controllers\PostController` devient
> `app/controllers/PostController.php`.

---

### Base de données

**Q : Expliquez la différence entre ON DELETE CASCADE et ON DELETE SET NULL.**

> La règle que j'ai appliquée est simple : CASCADE quand les données enfants n'ont pas
> de sens sans le parent, SET NULL quand elles conservent une valeur indépendante.
> Par exemple, une section de post n'a aucune utilité sans l'article auquel elle
> appartient — si l'article est supprimé, les sections doivent l'être aussi.
> En revanche, un article reste lisible même si sa catégorie est supprimée — on préfère
> passer category_id à NULL plutôt que de perdre tout le contenu éditorial.

**Q : Comment évitez-vous qu'un utilisateur like deux fois le même article ?**

> À deux niveaux. En base de données, j'ai défini une contrainte `UNIQUE KEY (post_id, user_id)`
> sur la table likes. Cela rend techniquement impossible l'insertion d'un doublon, même
> en cas de bug applicatif. Côté application, dans PostController::like(), je vérifie
> si l'utilisateur a déjà liké avec `Post::hasLiked()` avant d'insérer — si c'est le cas,
> je supprime le like (toggle). Le UNIQUE KEY est une protection défensive supplémentaire.

---

### Sécurité

**Q : Comment fonctionne votre protection CSRF ?**

> Le CSRF (Cross-Site Request Forgery) consiste pour un attaquant à faire exécuter une
> action à un utilisateur connecté sans son consentement. Ma protection fonctionne ainsi :
> à chaque formulaire POST, j'inclus un champ caché `_csrf` dont la valeur est un token
> aléatoire de 64 caractères hexadécimaux (`bin2hex(random_bytes(32))`), stocké en session.
> Quand le formulaire est soumis, le contrôleur compare le token reçu avec celui en session
> via `hash_equals()` — cette fonction est résistante aux attaques temporelles contrairement
> à `===`. Le token est renouvelé après chaque validation pour empêcher les replays.
> Un attaquant sur un autre domaine ne peut pas lire la valeur du token en session.

**Q : Qu'est-ce qu'une injection SQL et comment vous en protégez-vous ?**

> Une injection SQL consiste à injecter du code SQL malveillant dans un champ de formulaire.
> Par exemple, si on concatène directement `"WHERE id = " . $_GET['id']`, un attaquant peut
> passer `1 OR 1=1` et obtenir tous les enregistrements. Ma protection est systématique :
> j'utilise PDO avec des requêtes préparées et des paramètres liés (`:id`, `:slug`…).
> Le driver PDO traite les paramètres comme des données pures, jamais comme du SQL —
> les caractères spéciaux sont automatiquement échappés. Il n'y a aucune concaténation
> de variables dans les requêtes SQL dans tout le projet.

**Q : Qu'est-ce que le XSS et comment vous en protégez-vous ?**

> Le XSS (Cross-Site Scripting) consiste à injecter du code JavaScript dans une page web.
> Si un utilisateur soumet `<script>alert('hacked')</script>` comme commentaire et qu'on
> l'affiche sans traitement, le script s'exécuterait dans le navigateur de tous les lecteurs.
> Ma protection est `htmlspecialchars()` sur toutes les données utilisateur affichées dans
> les vues. Cette fonction convertit `<` en `&lt;`, `>` en `&gt;`, `"` en `&quot;` — le
> navigateur affiche le texte brut plutôt que d'interpréter le HTML.

---

### Frontend

**Q : Pourquoi avoir utilisé Tailwind CSS ?**

> Tailwind m'a permis de développer rapidement une interface cohérente sans écrire de
> feuille de style CSS monolithique. L'approche utility-first garantit qu'il n'y a pas
> de styles "orphelins" qui s'accumulent. J'ai complété Tailwind avec des variables CSS
> natives pour le système de thèmes clair/sombre — les variables changent selon la classe
> `.dark` sur la balise `html`, et la transition CSS `.3s ease` assure l'animation.
> Le tout est chargé via CDN sans processus de build, ce qui simplifie le déploiement.

**Q : Comment fonctionne votre mode sombre ?**

> Le mode sombre est géré entièrement côté client. Les deux thèmes sont définis via des
> variables CSS : `:root` pour le thème clair et `html.dark` pour le thème sombre.
> Le toggle JavaScript ajoute ou retire la classe `dark` sur la balise `html`.
> Le choix est persisté dans `localStorage` — au chargement de la page, on lit
> la valeur sauvegardée ; sinon on détecte la préférence système avec
> `window.matchMedia('(prefers-color-scheme: dark)')`. Aucune requête serveur n'est
> nécessaire pour changer de thème.

---

### Questions comportementales

**Q : Quelle a été la difficulté technique principale du projet ?**

> La difficulté principale a été le responsive mobile du header en mode sticky.
> Le problème était un bug de re-rendu en boucle : quand `overflow-x:auto` était posé
> directement sur un enfant d'un élément `position:sticky`, certains navigateurs
> déclenchaient un cycle de recalcul de layout infini, le header se réaffichant de plus
> en plus petit. La solution a été de placer `overflow:hidden` sur le wrapper parent
> et `overflow-x:auto` uniquement sur le div intérieur avec `min-width:max-content`,
> brisant le cycle de recalcul.

**Q : Qu'est-ce que vous feriez différemment si vous recommenciez ce projet ?**

> Je mettrais en place les tests unitaires dès le début. J'ai développé les contrôleurs
> et les modèles sans tests automatisés, ce qui signifie que chaque correction d'un bug
> pouvait potentiellement en introduire un autre sans que je le détecte immédiatement.
> PHPUnit aurait permis de sécuriser les régressions. Je mettrais également en place
> un fichier `.env.example` versionné dès le premier commit pour documenter toutes les
> variables requises.

**Q : Comment avez-vous géré le versionnement de votre code ?**

> J'ai utilisé Git avec GitHub. J'ai travaillé sur la branche `main` pour ce projet
> individuel, avec des commits atomiques et des messages descriptifs.
> *(Adapter cette réponse selon votre utilisation réelle de Git)*

---

## 3. Mots-clés techniques à maîtriser

Assurez-vous de pouvoir expliquer chacun de ces termes sans hésitation :

| Terme | Définition courte |
|---|---|
| MVC | Pattern architectural séparant Modèle, Vue et Contrôleur |
| Front Controller | Point d'entrée unique de l'application (index.php) |
| PDO | PHP Data Objects — couche d'abstraction pour accéder aux BDD |
| Requête préparée | Requête SQL avec paramètres liés, protège des injections |
| CSRF | Cross-Site Request Forgery — attaque par formulaire falsifié |
| XSS | Cross-Site Scripting — injection de JavaScript malveillant |
| bcrypt | Algorithme de hashage lent, adapté aux mots de passe |
| Session | Mécanisme de persistance côté serveur entre les requêtes HTTP |
| Cookie | Donnée persistée côté navigateur |
| Clé étrangère | Colonne référençant la PK d'une autre table |
| Cardinalité | Nature d'une relation : 1:1, 1:N, N:M |
| ON DELETE CASCADE | Suppression automatique des enfants avec le parent |
| Index BDD | Structure accélérant les recherches sur une colonne |
| Autoloader | Chargement automatique des classes PHP |
| RGPD | Règlement Général sur la Protection des Données |
| REST | Style d'architecture d'API basé sur les méthodes HTTP |
| Responsive | Adaptation de l'interface à toutes les tailles d'écran |
| localStorage | Stockage clé-valeur côté navigateur, persistant |

---

## 4. Ce que le jury valorise

- **La maîtrise** : pouvoir expliquer chaque ligne de code que vous avez écrite
- **La rigueur** : sécurité, validation, gestion des erreurs
- **La démarche** : montrer que vous avez pensé aux utilisateurs, pas seulement au code
- **L'honnêteté** : si vous ne savez pas quelque chose, dites-le — le jury préfère ça aux approximations
- **La progression** : montrer ce que vous avez appris, les difficultés surmontées

---

## 5. Checklist avant l'oral

- [ ] Tester le projet en conditions réelles (réseau, navigateur différent)
- [ ] Préparer des données de démonstration convaincantes (articles avec images, commentaires)
- [ ] Vérifier que le compte admin fonctionne
- [ ] Avoir le diagramme BDD exporté en image (dbdiagram.io)
- [ ] Avoir le design system HTML ouvert dans un onglet
- [ ] Préparer les devtools Chrome pour la démonstration responsive
- [ ] Relire les 7 fichiers de documentation
- [ ] S'entraîner à la présentation à voix haute (chronomètre)
- [ ] Préparer une URL GitHub publique avec un README complet

