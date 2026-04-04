# 02 – Choix techniques

## 1. Langage backend : PHP 8.3.14

**Justification :**

PHP est le langage enseigné dans le cadre de la formation et utilisé lors du stage. C'est aussi
le langage le plus répandu sur les hébergements mutualisés (Apache + PHP + MySQL), ce qui
facilite le déploiement sans nécessiter d'infrastructure complexe.

PHP 8.3 apporte des fonctionnalités modernes utilisées dans ce projet :
- **Types stricts** : déclaration des types de paramètres et retours dans les méthodes
- **Match expressions** : utilisées dans les vues pour les badges de statut (approved/pending/rejected)
- **Named arguments** : lisibilité améliorée des appels de fonctions
- **Nullsafe operator** (`?->`) : gestion élégante des valeurs nullables
- **`str_ends_with()` / `str_contains()`** : manipulation de chaînes dans les vues

**Pourquoi pas Node.js ou Python ?**
Ces technologies nécessitent des environnements de serveur spécifiques (Node runtime, pip, venv)
moins universellement disponibles sur hébergement mutualisé. PHP s'installe nativement avec Apache.

---

## 2. Base de données : MySQL 9.1.0

**Justification :**

MySQL est le SGBDR relationnel de référence dans l'écosystème PHP/Apache/WAMP. Il offre :
- **InnoDB** : moteur transactionnel avec support des clés étrangères et des contraintes CASCADE
- **utf8mb4** : encodage complet supportant les emojis et tous les caractères Unicode
- **Performances** : index sur les colonnes fréquemment interrogées (slug, status, created_at…)
- **Compatibilité hébergement** : disponible chez tous les hébergeurs mutualisés

Interface d'administration via **phpMyAdmin 5.2.1** pour la visualisation et la gestion.

---

## 3. Serveur local : WAMP (Windows 11)

**Justification :**

L'environnement WAMP (Windows + Apache + MySQL + PHP) est l'environnement de développement
standard pour les formations DWWM sous Windows. Il inclut :
- **Apache 2.4** avec `mod_rewrite` activé (nécessaire pour le routeur)
- **PHP 8.3.14** avec les extensions PDO, PDO_MySQL, mbstring activées
- **MySQL 9.1.0** accessible via phpMyAdmin
- Configuration simplifiée via l'interface graphique WAMP

---

## 4. Architecture : MVC personnalisé (sans framework)

**Justification :**

Le choix d'un MVC "from scratch" plutôt qu'un framework (Laravel, Symfony) est délibéré et
pédagogiquement fondé :

- **Compréhension profonde** : écrire son propre routeur, autoloader et contrôleur de base
  oblige à comprendre les mécanismes que les frameworks masquent
- **Légèreté** : aucune dépendance externe, le projet est entièrement maîtrisé
- **Certification DWWM** : le jury valorise la capacité à construire sans filet de sécurité
- **Portabilité** : aucun `composer install` requis pour lancer le projet

**Pourquoi pas Laravel ?**
Laravel est un excellent framework mais il introduit une complexité (Eloquent, Blade, Artisan,
Composer, .env Dotenv, queues…) qui masque les fondamentaux évalués en certification DWWM.

---

## 5. Frontend : Tailwind CSS (CDN)

**Justification :**

Tailwind CSS est utilisé via CDN (Play CDN) pour éviter toute dépendance à Node.js, npm ou
un processus de build. Ce choix est cohérent avec l'objectif d'un projet déployable sans
outillage complexe.

L'utilisation de Tailwind est complétée par :
- **CSS variables** (`--bg`, `--text`, `--border`…) pour le système de thèmes clair/sombre
- **Styles inline** ciblés pour les composants nécessitant une précision que les classes
  utilitaires Tailwind ne permettent pas (hover JavaScript, transitions conditionnelles)

**Typographie :** Google Fonts avec deux familles complémentaires :
- **Playfair Display** (titres) : caractère éditorial et élégant, évoque la presse locale
- **Source Sans 3** (corps) : lisibilité optimale pour le texte long

---

## 6. Graphiques : Chart.js

**Justification :**

Chart.js est la bibliothèque de visualisation la plus légère et la plus simple à intégrer
dans un projet PHP pur. Elle est chargée via CDN sans dépendance supplémentaire.

Utilisée dans le dashboard admin pour 4 types de graphiques :
- **Barres** : publications par mois
- **Doughnut** : répartition publiés/brouillons
- **Barres horizontales** : répartition par catégorie
- **Ligne** : inscriptions admins vs membres par mois

---

## 7. Récapitulatif des dépendances externes

Toutes les dépendances sont chargées via CDN — aucune installation locale requise.

|    Dépendance    |  Version  |        Source        |           Usage           |
|------------------|-----------|----------------------|---------------------------|
| Tailwind CSS     | Play CDN  | cdn.tailwindcss.com  | Framework CSS utilitaire  |
| Playfair Display | —         | Google Fonts         | Police titres             |
| Source Sans 3    | —         | Google Fonts         | Police corps de texte     |
| Chart.js         | latest    | cdn.jsdelivr.net     | Graphiques dashboard      |

