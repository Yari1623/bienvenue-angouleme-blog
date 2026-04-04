# DÉPLOIEMENT EN LIGNE — Guide pas à pas
# Bienvenue à Angoulême — TP DWWM

---

## OPTION A — InfinityFree (gratuit, ~30 min)

### Étape 1 — Créer le compte
1. Aller sur https://infinityfree.net
2. Sign Up → email + mot de passe
3. Confirmer l'email

### Étape 2 — Créer un hébergement
1. Dashboard → "New Account"
2. Choisir un sous-domaine gratuit (ex: bienvenue-angouleme.rf.gd)
   OU connecter un domaine personnalisé si vous en avez un
3. Cliquer "Create Account"
4. Attendre ~2 minutes l'activation

### Étape 3 — Créer la base de données
1. Dans le dashboard InfinityFree → cliquer sur votre compte
2. Cliquer "Control Panel" → "MySQL Databases"
3. Créer une base de données → noter :
   - DB Host (ex: sql123.infinityfree.net)
   - DB Name (préfixé automatiquement, ex: if0_12345678_bienvenue_blog)
   - DB User (même que DB Name)
   - DB Password (choisir un mot de passe fort)
4. Accéder à phpMyAdmin via le Control Panel
5. Sélectionner votre base → onglet "Import"
6. Choisir database/schema.sql → Exécuter

### Étape 4 — Préparer le fichier .env
Créer un fichier .env LOCAL avec vos vrais paramètres :
```
DB_HOST=sql123.infinityfree.net
DB_PORT=3306
DB_NAME=if0_12345678_bienvenue_blog
DB_USER=if0_12345678_bienvenue_blog
DB_PASS=VotreMotDePasseForte
APP_ENV=production
APP_DEBUG=false
```

### Étape 5 — Uploader les fichiers via FTP

**Paramètres FTP InfinityFree :**
- Host : ftpupload.net
- User : votre username InfinityFree
- Password : votre mot de passe
- Port : 21

**Logiciel recommandé : FileZilla (gratuit)**
1. Télécharger FileZilla : https://filezilla-project.org
2. Fichier → Gestionnaire de sites → Nouveau site
3. Remplir Host, User, Password, Port
4. Connexion

**Structure à uploader :**
- Dans FileZilla, dossier distant = /htdocs/
- Uploader TOUT le contenu du projet SAUF :
  - Ne PAS uploader .env (vous le créerez directement sur le serveur)
  - Ne PAS uploader .git/
  - Ne PAS uploader node_modules/ (si présent)

### Étape 6 — Créer le fichier .env sur le serveur
1. Dans FileZilla, naviguer vers /htdocs/
2. Clic droit → "Créer un nouveau fichier" → nommer ".env"
3. Clic droit sur le fichier → "Voir/Modifier"
4. Coller le contenu du .env préparé à l'étape 4

### Étape 7 — Configurer le DocumentRoot
InfinityFree utilise /htdocs/ comme racine web publique.
Votre projet doit être organisé ainsi :
```
/htdocs/
├── public/          ← contenu de votre public/
│   ├── index.php
│   ├── .htaccess
│   └── assets/
├── app/
├── config/
├── views/
└── .env
```

**ATTENTION :** Le document root d'InfinityFree pointe vers /htdocs/.
Votre public/ doit donc être à la racine de /htdocs/.

Option simple : uploader TOUT votre projet dans /htdocs/, puis créer
un .htaccess à la racine /htdocs/ qui redirige vers public/ :

```apache
# /htdocs/.htaccess — redirige vers le sous-dossier public/
RewriteEngine On
RewriteCond %{REQUEST_URI} !^/public/
RewriteRule ^(.*)$ /public/$1 [L]
```

### Étape 8 — Vérifier que ça fonctionne
1. Aller sur votre URL (ex: https://bienvenue-angouleme.rf.gd)
2. Vérifier que la page d'accueil s'affiche
3. Tester la connexion admin
4. Changer immédiatement le mot de passe admin par défaut !

---

## OPTION B — o2switch (recommandé, ~7€/mois)

### Pourquoi o2switch ?
- PHP 8.3 natif (compatible avec votre projet)
- MySQL 9.x disponible
- Serveurs en France (conformité RGPD)
- cPanel + phpMyAdmin inclus
- SSL Let's Encrypt gratuit
- Support technique en français

### Procédure
1. Commander sur https://www.o2switch.fr (offre "Unique" ~7€/mois)
2. Recevoir les identifiants cPanel par email
3. Se connecter au cPanel → MySQL Databases → créer BDD + user
4. Importer schema.sql via phpMyAdmin
5. Uploader les fichiers via FTP (identiques à InfinityFree)
6. Dans cPanel → "Modifier le répertoire racine" du domaine → pointer vers /public/
   OU via "MultiPHP Manager" configurer PHP 8.3
7. Créer le fichier .env avec les paramètres o2switch

### Avantage o2switch vs InfinityFree
- Possibilité de pointer le DocumentRoot directement vers /public/
  sans besoin d'un .htaccess supplémentaire
- Pas de limitation de bande passante
- Accès SSH disponible (déploiement git possible)

---

## CHECKLIST POST-DÉPLOIEMENT

- [ ] La page d'accueil s'affiche correctement
- [ ] Le mode sombre fonctionne
- [ ] La connexion admin fonctionne
- [ ] Un article s'affiche correctement
- [ ] Les images se chargent (vérifier les chemins BASE_URL)
- [ ] Le formulaire de contact ne génère pas d'erreur
- [ ] APP_DEBUG=false dans .env (pas d'affichage d'erreurs PHP)
- [ ] Le mot de passe admin par défaut a été changé
- [ ] HTTPS fonctionne (cadenas vert dans le navigateur)

---

## DÉPANNAGE FRÉQUENT

### Erreur 500 — Internal Server Error
→ Vérifier APP_DEBUG=true temporairement pour voir l'erreur
→ Vérifier les paramètres BDD dans .env
→ Vérifier que AllowOverride All est activé (apache)

### Page blanche / 404 sur toutes les pages sauf accueil
→ Le .htaccess n'est pas pris en compte
→ Vérifier AllowOverride All dans la config Apache
→ Vérifier que mod_rewrite est activé

### Images qui ne s'affichent pas
→ Vérifier BASE_URL dans index.php ou config
→ Les chemins doivent être absolus depuis la racine du site

### Erreur PDO — connexion BDD impossible
→ Vérifier DB_HOST (souvent localhost sur o2switch, sql***.infinityfree.net sur InfinityFree)
→ Vérifier DB_NAME, DB_USER, DB_PASS dans .env