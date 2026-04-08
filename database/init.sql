-- =============================================================
-- database/init.sql
-- Base de données : bienvenue_blog
-- Moteur         : MySQL 9.x / MariaDB 10.x
-- Encodage       : utf8mb4 (support emojis et caractères spéciaux)
-- Généré le      : 2026-03-21
--
-- Ordre de création respecté pour les clés étrangères :
--   1. users, categories, places
--   2. posts (dépend de users, categories, places)
--   3. post_sections (dépend de posts)
--   4. post_categories (dépend de posts, categories)
--   5. comments, likes, post_views (dépend de posts, users)
--   6. events
--   7. event_interests (dépend de events, users)
-- =============================================================
 
-- Suppression de la base si elle existe déjà (à utiliser avec précaution)
DROP DATABASE IF EXISTS bienvenue_blog;
CREATE DATABASE bienvenue_blog
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
 
USE bienvenue_blog;
 
-- =============================================================
-- TABLE : users
-- Comptes membres et administrateurs
-- =============================================================
CREATE TABLE users (
    id          INT           NOT NULL AUTO_INCREMENT,
    username    VARCHAR(80)   NOT NULL UNIQUE,
    first_name  VARCHAR(100)  DEFAULT NULL,
    last_name   VARCHAR(100)  DEFAULT NULL,
    company     VARCHAR(150)  DEFAULT NULL,
    phone       VARCHAR(30)   DEFAULT NULL,
    email       VARCHAR(180)  NOT NULL UNIQUE,
    password    VARCHAR(255)  NOT NULL,           -- hash bcrypt
    role        ENUM('member','admin') NOT NULL DEFAULT 'member',
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_users_email    (email),
    INDEX idx_users_username (username),
    INDEX idx_users_role     (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : categories
-- Catégories des articles (Actualités, Culture, Sport…)
-- =============================================================
CREATE TABLE categories (
    id         INT          NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100) NOT NULL UNIQUE,
    slug       VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : places
-- Zones géographiques des articles (Angoulême, Charente, France…)
-- =============================================================
CREATE TABLE places (
    id          INT          NOT NULL AUTO_INCREMENT,
    name        VARCHAR(100) NOT NULL UNIQUE,
    slug        VARCHAR(150) NOT NULL UNIQUE,
    description TEXT         DEFAULT NULL,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_places_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : posts
-- Articles du blog
-- =============================================================
CREATE TABLE posts (
    id           INT           NOT NULL AUTO_INCREMENT,
    title        VARCHAR(255)  NOT NULL,
    slug         VARCHAR(300)  NOT NULL UNIQUE,
    content      TEXT          DEFAULT NULL,       -- intro / résumé
    thumbnail    VARCHAR(500)  DEFAULT NULL,       -- URL image principale
    status       ENUM('draft','published') NOT NULL DEFAULT 'draft',
    author_id    INT           NOT NULL,
    category_id  INT           DEFAULT NULL,
    place_id     INT           DEFAULT NULL,
    tags         VARCHAR(500)  DEFAULT NULL,       -- CSV ex: "culture,bd,festival"
    reading_time TINYINT UNSIGNED DEFAULT NULL,    -- en minutes
    created_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_posts_slug        (slug),
    INDEX idx_posts_status      (status),
    INDEX idx_posts_author      (author_id),
    INDEX idx_posts_category    (category_id),
    INDEX idx_posts_place       (place_id),
    INDEX idx_posts_created     (created_at),
    CONSTRAINT fk_posts_author   FOREIGN KEY (author_id)   REFERENCES users(id)      ON DELETE CASCADE,
    CONSTRAINT fk_posts_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    CONSTRAINT fk_posts_place    FOREIGN KEY (place_id)    REFERENCES places(id)     ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : post_sections
-- Blocs de contenu de l'éditeur (texte, titre, image, vidéo, citation, galerie)
-- =============================================================
CREATE TABLE post_sections (
    id        INT      NOT NULL AUTO_INCREMENT,
    post_id   INT      NOT NULL,
    type      ENUM('text','title','image','video','quote','gallery') NOT NULL,
    content   TEXT     DEFAULT NULL,    -- texte ou légende
    media_url TEXT     DEFAULT NULL,    -- URL image/vidéo ou liste CSV pour galerie
    position  TINYINT  NOT NULL DEFAULT 0,
    PRIMARY KEY (id),
    INDEX idx_post_sections_post     (post_id),
    INDEX idx_post_sections_position (post_id, position),
    CONSTRAINT fk_sections_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : post_categories (liaison many-to-many — peu utilisée)
-- Permet à un article d'appartenir à plusieurs catégories
-- =============================================================
CREATE TABLE post_categories (
    post_id     INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (post_id, category_id),
    CONSTRAINT fk_pc_post     FOREIGN KEY (post_id)     REFERENCES posts(id)      ON DELETE CASCADE,
    CONSTRAINT fk_pc_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : comments
-- Commentaires des membres sur les articles
-- Statut pending → modération avant publication
-- =============================================================
CREATE TABLE comments (
    id         INT      NOT NULL AUTO_INCREMENT,
    post_id    INT      NOT NULL,
    user_id    INT      NOT NULL,
    content    TEXT     NOT NULL,
    status     ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_comments_post    (post_id),
    INDEX idx_comments_user    (user_id),
    INDEX idx_comments_status  (status),
    INDEX idx_comments_created (created_at),
    CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts(id)  ON DELETE CASCADE,
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : likes
-- J'aimes des membres sur les articles
-- UNIQUE(post_id, user_id) → un seul like par membre par article
-- =============================================================
CREATE TABLE likes (
    id         INT       NOT NULL AUTO_INCREMENT,
    post_id    INT       NOT NULL,
    user_id    INT       NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_likes (post_id, user_id),
    CONSTRAINT fk_likes_post FOREIGN KEY (post_id) REFERENCES posts(id)  ON DELETE CASCADE,
    CONSTRAINT fk_likes_user FOREIGN KEY (user_id) REFERENCES users(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : post_views
-- Vues des articles — déduplication par user_id ou ip_address
-- user_id NULL pour les visiteurs non connectés
-- =============================================================
CREATE TABLE post_views (
    id         INT          NOT NULL AUTO_INCREMENT,
    post_id    INT          NOT NULL,
    user_id    INT          DEFAULT NULL,
    ip_address VARCHAR(45)  DEFAULT NULL,   -- IPv4 ou IPv6
    viewed_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_views_post    (post_id),
    INDEX idx_views_user    (user_id),
    INDEX idx_views_created (viewed_at),
    CONSTRAINT fk_views_post FOREIGN KEY (post_id) REFERENCES posts(id)  ON DELETE CASCADE,
    CONSTRAINT fk_views_user FOREIGN KEY (user_id) REFERENCES users(id)  ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : events
-- Événements de l'agenda local
-- =============================================================
CREATE TABLE events (
    id          INT          NOT NULL AUTO_INCREMENT,
    title       VARCHAR(255) NOT NULL,
    description TEXT         DEFAULT NULL,
    event_date  DATE         NOT NULL,
    event_time  TIME         DEFAULT NULL,
    location    VARCHAR(255) DEFAULT NULL,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_events_date (event_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
-- =============================================================
-- TABLE : event_interests
-- Membres intéressés par un événement
-- UNIQUE(user_id, event_id) → un seul intérêt par membre par événement
-- =============================================================
CREATE TABLE event_interests (
    id         INT       NOT NULL AUTO_INCREMENT,
    user_id    INT       NOT NULL,
    event_id   INT       NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_event_interests (user_id, event_id),
    CONSTRAINT fk_ei_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_ei_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
 
-- =============================================================
-- DONNÉES INITIALES : catégories
-- =============================================================
INSERT INTO categories (name, slug) VALUES
('Actualités',            'actualites'),
('Animaux',               'animaux'),
('Architecture & Patrimoine', 'architecture-et-patrimoine'),
('Commerce',              'commerce'),
('Culture',               'culture'),
('Divers',                'divers'),
('Évènement',             'evenement'),
('High-tech',             'high-tech'),
('Musique',               'musique'),
('Nature & Environnement','nature-et-environnement'),
('Social',                'social'),
('Sports',                'sport');
 
-- =============================================================
-- DONNÉES INITIALES : lieux
-- =============================================================
INSERT INTO places (name, slug, description) VALUES
('Angoulême',          'angouleme',          'Ville d\'Angoulême et son centre historique'),
('Grand Angoulême',    'grand-angouleme',     'Communauté d\'agglomération du Grand Angoulême'),
('Charente',           'charente',            'Département de la Charente (16)'),
('Poitou-Charentes',   'poitou-charentes',    'Ancienne région Poitou-Charentes'),
('Nouvelle-Aquitaine', 'nouvelle-aquitaine',  'Région Nouvelle-Aquitaine'),
('France',             'france',              'France métropolitaine'),
('Europe',             'europe',              'Union Européenne et Europe'),
('Monde',              'monde',               'International et monde entier');
 
 
-- =============================================================
-- COMPTE ADMINISTRATEUR PAR DÉFAUT
-- IMPORTANT : changer le mot de passe immédiatement après l'import !
-- Mot de passe par défaut : Admin1234!
-- Hash bcrypt généré avec password_hash('Admin1234!', PASSWORD_BCRYPT)
-- =============================================================
INSERT INTO users (username, first_name, last_name, email, password, role) VALUES
(
    'Admin',
    'Yannick',
    'Richard',
    'admin@bienvenue-angouleme.fr',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', -- Admin1234!
    'admin'
);
-- ⚠️  Remplacez ce hash par le vôtre via :
--     php -r "echo password_hash('VotreMotDePasse', PASSWORD_BCRYPT);"