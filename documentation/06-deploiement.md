# 06 – Déploiement

## Environnements

| Environnement |            Serveur           |                        URL                       |
|---------------|------------------------------|--------------------------------------------------|
| Développement | WAMP local (Windows 11)      | http://localhost/bienvenue-angouleme-blog/public |
| Production    | Hébergement mutualisé Apache | https://bienvenue-angouleme.fr (à configurer)    |

---

## Prérequis serveur

|         Composant         | Version minimale | Version utilisée |
|---------------------------|------------------|------------------|
| PHP                       | 8.1+             | 8.3.14           |
| MySQL / MariaDB           | 5.7+ / 10.3+     | 9.1.0            |
| Apache                    | 2.4+             | 2.4.x            |
| Extension PHP PDO         | —                | Requise          |
| Extension PHP PDO_MySQL   | —                | Requise          |
| Extension PHP mbstring    | —                | Requise          |
| Module Apache mod_rewrite | —                | Requis           |

---

## Structure des dossiers

```
bienvenue-angouleme-blog/
├── public/           ← DOCUMENT ROOT — seul dossier accessible depuis le web
│   ├── index.php     ← Front Controller
│   ├── .htaccess     ← Réécriture d'URL + sécurisation
│   └── assets/       ← Images, icônes, fichiers statiques
├── app/              ← Code PHP (non accessible depuis le web)
├── config/           ← Configuration (non accessible depuis le web)
├── views/            ← Vues PHP (non accessibles depuis le web)
├── database/
│   └── schema.sql    ← Script de création de la base de données
├── .env              ← Variables d'environnement (NE PAS VERSIONNER)
└── .env.example      ← Modèle de configuration (versionné)
```

**Important :** Le `DocumentRoot` Apache doit pointer vers le sous-dossier `public/`
et non vers la racine du projet. Cela garantit que les dossiers `app/`, `config/`,
`views/` et le fichier `.env` ne sont jamais accessibles directement depuis un navigateur.

---

## Procédure d'installation (développement local)

### Étape 1 — Cloner le dépôt
```bash
git clone https://github.com/[username]/bienvenue-angouleme-blog.git
cd bienvenue-angouleme-blog
```

### Étape 2 — Créer le fichier .env
```bash
cp .env.example .env
```
Éditer `.env` avec les paramètres locaux :
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=bienvenue_blog
DB_USER=root
DB_PASS=
APP_ENV=development
APP_DEBUG=true
```

### Étape 3 — Importer la base de données
Via phpMyAdmin :
1. Créer une nouvelle base `bienvenue_blog` (utf8mb4_unicode_ci)
2. Onglet Import → sélectionner `database/init.sql`
3. Cliquer Exécuter

Ou en ligne de commande :
```bash
mysql -u root -p < database/init.sql
```

### Étape 4 — Configurer le VirtualHost WAMP (optionnel)
Pour accéder via `http://bienvenue-angouleme.local` :
```apache
<VirtualHost *:80>
    ServerName bienvenue-angouleme.local
    DocumentRoot "C:/wamp64/www/bienvenue-angouleme-blog/public"
    <Directory "C:/wamp64/www/bienvenue-angouleme-blog/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
Ajouter `127.0.0.1 bienvenue-angouleme.local` dans `C:\Windows\System32\drivers\etc\hosts`.

### Étape 5 — Vérifier le .htaccess
S'assurer que `AllowOverride All` est bien activé dans la configuration Apache pour que
le fichier `.htaccess` soit pris en compte (réécriture d'URL).

---

## Procédure de déploiement en production (hébergement mutualisé)

### Étape 1 — Préparer les fichiers
```bash
# Sur le poste local, s'assurer que .env n'est pas dans le dépôt
echo ".env" >> .gitignore
git push origin main
```

### Étape 2 — Transférer les fichiers
Via FTP (FileZilla) ou le gestionnaire de fichiers cPanel :
- Uploader le contenu du projet dans le répertoire de l'hébergement
- Le `DocumentRoot` doit pointer vers le sous-dossier `public/`

### Étape 3 — Créer la base de données
Via cPanel → MySQL Databases :
1. Créer une base de données
2. Créer un utilisateur MySQL avec mot de passe fort
3. Associer l'utilisateur à la base avec tous les privilèges
4. Importer `database/init.sql` via phpMyAdmin

### Étape 4 — Configurer .env en production
Créer le fichier `.env` directement sur le serveur (via FTP ou SSH) :
```
DB_HOST=localhost
DB_PORT=3306
DB_NAME=nom_de_la_base
DB_USER=nom_utilisateur_mysql
DB_PASS=mot_de_passe_fort
APP_ENV=production
APP_DEBUG=false
```

### Étape 5 — Changer le mot de passe administrateur
Le compte admin par défaut du `init.sql` doit être changé immédiatement :
1. Se connecter avec `admin@bienvenue-angouleme.fr` / `Admin1234!`
2. Aller dans Mon compte → Changer le mot de passe
3. Définir un mot de passe fort

### Étape 6 — Vérifications post-déploiement
- [ ] Le site est accessible via HTTPS
- [ ] La redirection HTTP → HTTPS fonctionne
- [ ] La page d'accueil s'affiche correctement
- [ ] La connexion admin fonctionne
- [ ] La création d'article fonctionne
- [ ] Le formulaire de contact fonctionne (configuration SMTP requise)
- [ ] `APP_DEBUG=false` dans `.env`

---

## Configuration SMTP (formulaire de contact)

Le formulaire de contact nécessite une configuration SMTP pour l'envoi d'emails.
À ajouter dans `.env` :
```
MAIL_HOST=mail.bienvenue-angouleme.fr
MAIL_PORT=587
MAIL_USER=contact@bienvenue-angouleme.fr
MAIL_PASS=mot_de_passe_email
MAIL_FROM=contact@bienvenue-angouleme.fr
```

---

## Hébergeur recommandé

**o2switch** (hébergeur français) :
- PHP 8.3 disponible
- MySQL 8.0+
- Apache avec mod_rewrite
- cPanel + phpMyAdmin inclus
- Certificat SSL Let's Encrypt gratuit
- Serveurs en France (RGPD)

