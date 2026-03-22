# 01 – Présentation du projet

## Nom du projet
**Bienvenue à Angoulême – Le Blog Local**

## Contexte et genèse

Ce projet a été réalisé dans le cadre de la formation **Développeur Web et Web Mobile (DWWM)**
auprès d'un organisme de formation agréé, en vue du passage du titre professionnel de niveau 5
(équivalent Bac+2) inscrit au RNCP.

L'idée est née d'un constat simple : Angoulême, ville d'art, de culture et de bande dessinée,
manque d'un blog local moderne, accessible et participatif. Le projet répond à ce besoin en
proposant une plateforme éditoriale complète où les habitants, touristes et passionnés d'actualité
locale peuvent s'informer, commenter et s'impliquer dans la vie de la cité.

## Périmètre fonctionnel

Le projet couvre l'intégralité d'un blog professionnel :

### Interface publique
- Page d'accueil avec sections dynamiques (derniers articles, plus lus, plus commentés)
- Blog avec recherche full-text, filtres par catégorie, lieu, tag et tri
- Page de détail d'un article avec éditeur de contenu par blocs (texte, image, vidéo, citation, galerie)
- Agenda des événements locaux avec système d'intérêt
- Page des catégories et zones géographiques cliquables
- Profil utilisateur (likes, historique de lecture, événements suivis)
- Gestion du compte (modification des informations, changement de mot de passe)
- Pages légales complètes (mentions légales, RGPD, politique de confidentialité, cookies)
- Formulaire de contact
- Page Histoire d'Angoulême
- Mode clair / sombre avec persistance localStorage

### Espace administration
- Tableau de bord avec statistiques et 4 graphiques Chart.js
- Gestion complète des articles (CRUD, publication/dépublication, éditeur par blocs)
- Modération des commentaires (approuver, refuser, supprimer)
- Gestion des utilisateurs (rôles admin/membre, modification, suppression)
- Gestion des catégories et des lieux
- Gestion de l'agenda (création, modification, suppression d'événements)

### Authentification
- Inscription et connexion sécurisées
- Système de rôles : visiteur, membre, administrateur
- Protection de toutes les routes sensibles

## Chiffres clés du projet

| Indicateur | Valeur |
|---|---|
| Tables en base de données | 10 |
| Controllers | 18 |
| Modèles | 6 |
| Fichiers de vues | ~45 |
| Routes déclarées | ~40 |
| Articles de test | 48 |
| Utilisateurs de test | 10 |
| Durée de développement | ~6 semaines |

## Public cible

- **Habitants d'Angoulême** : suivre l'actualité locale, commenter, s'informer des événements
- **Touristes** : découvrir la ville, son patrimoine, sa culture
- **Associations et acteurs locaux** : relayer leurs actualités via l'admin
- **Passionnés de BD** : la ville capitale mondiale de la bande dessinée

## Stack technique résumée

| Couche | Technologie |
|---|---|
| Backend | PHP 8.3.14 |
| Base de données | MySQL 9.1.0 |
| Architecture | MVC personnalisé (sans framework) |
| Frontend | Tailwind CSS CDN + CSS variables |
| Typographie | Google Fonts (Playfair Display + Source Sans 3) |
| Graphiques | Chart.js |
| Serveur local | WAMP (Apache 2.4 + PHP 8.3 + MySQL 9.1) |
| Versionnement | Git / GitHub |

## Lien GitHub

> À compléter avec l'URL du dépôt public avant la soutenance.
