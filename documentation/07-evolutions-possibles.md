# 07 – Évolutions possibles

Ce document présente les évolutions envisagées pour la prochaine version du blog,
classées par ordre de priorité et de valeur ajoutée.

---

## 1. API REST (priorité haute)

**Contexte :** Actuellement, le blog est une application web monolithique — le backend
PHP génère directement le HTML. Une API REST découplerait le backend du frontend.

**Ce que ça apporterait :**
- Permettre la création d'une application mobile native (iOS/Android) consommant les mêmes données
- Ouvrir le contenu à des partenaires (agrégateurs d'actualités locales)
- Faciliter l'intégration avec des outils tiers (réseaux sociaux, newsletters)

**Implémentation envisagée :**
- Endpoints JSON : `GET /api/posts`, `GET /api/posts/{slug}`, `GET /api/events`
- Authentification par token JWT pour les endpoints protégés
- Versionnement : `/api/v1/`

---

## 2. Système de newsletter (priorité haute)

**Contexte :** Les lecteurs réguliers n'ont pas de moyen de s'abonner aux nouveaux articles.

**Ce que ça apporterait :**
- Fidélisation des lecteurs (notification automatique à chaque nouvel article)
- Promotion des événements de l'agenda local
- Indicateurs d'engagement (taux d'ouverture, de clic)

**Implémentation envisagée :**
- Formulaire d'abonnement avec double opt-in (RGPD)
- Table `newsletter_subscribers` en BDD
- Envoi via SMTP ou service tiers (Mailchimp, Brevo)
- Lien de désinscription dans chaque email

---

## 3. Recherche avancée avec Elasticsearch (priorité moyenne)

**Contexte :** La recherche actuelle est un `LIKE %terme%` MySQL — fonctionnelle mais
limitée en termes de pertinence et de performance sur de grands volumes.

**Ce que ça apporterait :**
- Recherche full-text avec pertinence (score de correspondance)
- Tolérance aux fautes de frappe (fuzzy search)
- Suggestions de complétion automatique
- Performance sur des milliers d'articles

**Implémentation envisagée :**
- Indexation des articles dans Elasticsearch à chaque création/modification
- Remplacement de la requête SQL LIKE par une requête Elasticsearch
- Interface de recherche avec filtres facettés

---

## 4. Pagination AJAX (priorité moyenne)

**Contexte :** Actuellement, chaque changement de page recharge toute la page HTML.

**Ce que ça apporterait :**
- Expérience utilisateur plus fluide (chargement instantané)
- Réduction de la bande passante (seuls les articles changent, pas le header/footer)
- Défilement infini possible (infinite scroll)

**Implémentation envisagée :**
- Endpoints JSON pour les listes d'articles (`/api/posts?page=2&categorie=culture`)
- JavaScript Fetch API pour charger les articles sans rechargement
- Maintien du scroll et de l'URL (History API)

---

## 5. Mise en cache (priorité moyenne)

**Contexte :** Chaque requête HTTP régénère entièrement le HTML, même pour des pages
qui changent rarement (page d'accueil, liste des catégories).

**Ce que ça apporterait :**
- Temps de réponse divisés par 5 à 10 sur les pages statiques
- Réduction de la charge sur MySQL
- Meilleure résistance aux pics de trafic

**Implémentation envisagée :**
- Cache fichier (dossier `cache/`) pour les pages les plus consultées
- Invalidation du cache à chaque publication d'article
- Redis ou Memcached pour les environnements à fort trafic

---

## 6. Éditeur de contenu enrichi (WYSIWYG) (priorité moyenne)

**Contexte :** L'éditeur actuel par blocs est fonctionnel mais textuel (inputs HTML).

**Ce que ça apporterait :**
- Interface d'édition plus intuitive pour les rédacteurs non techniques
- Aperçu en temps réel du rendu final
- Gestion des médias intégrée (upload d'images directement dans l'éditeur)

**Implémentation envisagée :**
- Intégration de TipTap ou Quill.js comme éditeur WYSIWYG
- Upload d'images vers un dossier `public/uploads/` avec validation du type MIME
- Conversion du HTML produit en blocs `post_sections`

---

## 7. Progressive Web App (priorité basse)

**Contexte :** Le blog est actuellement une application web classique.

**Ce que ça apporterait :**
- Installation sur l'écran d'accueil du smartphone (comme une app native)
- Mode hors-ligne : lecture des articles déjà consultés sans connexion
- Notifications push : alerter les abonnés des nouveaux articles

**Implémentation envisagée :**
- `manifest.json` pour la définition de l'app (icône, nom, couleurs)
- Service Worker pour le cache offline des assets et articles
- API Push Notifications (avec consentement RGPD explicite)

---

## 8. Statistiques avancées (priorité basse)

**Contexte :** Le dashboard actuel propose des statistiques basiques (vues, likes, commentaires).

**Ce que ça apporterait :**
- Compréhension approfondie des habitudes de lecture
- Identification des meilleurs créneaux de publication
- Mesure de l'engagement par catégorie et par auteur

**Implémentation envisagée :**
- Heatmap des heures de visite
- Taux de rebond par article
- Origine du trafic (referrer)
- Export CSV des statistiques

---

## Récapitulatif des priorités

| Évolution | Impact UX | Complexité | Priorité |
|---|---|---|---|
| API REST | Élevé | Moyenne | Haute |
| Newsletter | Élevé | Faible | Haute |
| Recherche avancée | Moyen | Élevée | Moyenne |
| Pagination AJAX | Moyen | Faible | Moyenne |
| Mise en cache | Faible (invisible) | Moyenne | Moyenne |
| Éditeur WYSIWYG | Élevé (admin) | Moyenne | Moyenne |
| PWA | Moyen | Élevée | Basse |
| Statistiques avancées | Moyen (admin) | Moyenne | Basse |

