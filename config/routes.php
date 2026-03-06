<?php

// ============================================================
// Routes publiques — Général
// ============================================================

$router->get('',                            'HomeController@index');
$router->get('blog',                        'HomeController@index');

// Articles
$router->get('article/{slug}',              'PostController@show');
$router->post('article/{slug}/comment',     'PostController@comment');
$router->post('article/{slug}/like',        'PostController@like');

// Catégories
$router->get('categories',                  'CategoriesController@index');
$router->get('categorie/{slug}',            'CategoryController@show');

// Agenda / événements
$router->get('agenda',                      'EventController@index');
$router->post('agenda/{id}/interest',       'EventController@toggleInterest');

// À propos
$router->get('a-propos',                    'AboutController@index');

// Contact
$router->get('contact',                     'ContactController@index');
$router->post('contact/send',               'ContactController@send');

// Profil (membre connecté)
$router->get('profil',                      'ProfileController@index',          true);

// Auth
$router->get('login',                       'LoginController@show');
$router->post('login',                      'LoginController@login');
$router->get('logout',                      'LoginController@logout');
$router->post('logout-beacon',              'LoginController@logoutBeacon');
$router->get('register',                    'RegisterController@show');
$router->post('register',                   'RegisterController@register');

// ============================================================
// Mentions légales & RGPD
// ============================================================

$router->get('mentions-legales',            'LegalController@mentions');
$router->get('politique-confidentialite',   'LegalController@privacy');
$router->get('politique-cookies',           'LegalController@cookies');
$router->get('rgpd',                        'LegalController@rgpd');

// ============================================================
// Routes admin — tableau de bord
// ============================================================

$router->get('admin',                       'AdminController@index',            true, 'admin');

// ============================================================
// Routes admin — articles
// ============================================================

$router->get('admin/posts',                 'PostController@index',             true, 'admin');
$router->get('admin/posts/create',          'PostController@create',            true, 'admin');
$router->post('admin/posts/store',          'PostController@store',             true, 'admin');
$router->get('admin/posts/{id}/edit',       'PostController@edit',              true, 'admin');
$router->post('admin/posts/{id}/update',    'PostController@update',            true, 'admin');
$router->post('admin/posts/{id}/delete',    'PostController@delete',            true, 'admin');
$router->post('admin/posts/{id}/status',    'PostController@toggleStatus',      true, 'admin');

// ============================================================
// Routes admin — commentaires
// ============================================================

$router->get('admin/comments',              'CommentController@index',          true, 'admin');
$router->post('admin/comments/{id}/approve','CommentController@approve',        true, 'admin');
$router->post('admin/comments/{id}/reject', 'CommentController@reject',         true, 'admin');
$router->post('admin/comments/{id}/delete', 'CommentController@delete',         true, 'admin');

// ============================================================
// Routes admin — catégories
// ============================================================

$router->get('admin/categories',                    'CategoryController@index',  true, 'admin');
$router->get('admin/categories/create',             'CategoryController@create', true, 'admin');
$router->post('admin/categories/store',             'CategoryController@store',  true, 'admin');
$router->get('admin/categories/{id}/edit',          'CategoryController@edit',   true, 'admin');
$router->post('admin/categories/{id}/update',       'CategoryController@update', true, 'admin');
$router->post('admin/categories/{id}/delete',       'CategoryController@delete', true, 'admin');

// ============================================================
// Routes admin — utilisateurs
// ============================================================

$router->get('admin/users',                 'UserController@index',             true, 'admin');
$router->get('admin/users/{id}/edit',       'UserController@edit',              true, 'admin');
$router->post('admin/users/{id}/update',    'UserController@update',            true, 'admin');
$router->post('admin/users/{id}/role',      'UserController@updateRole',        true, 'admin');
$router->post('admin/users/{id}/delete',    'UserController@delete',            true, 'admin');

// ============================================================
// Routes admin — événements
// ============================================================

$router->get('admin/events',                'EventController@adminIndex',       true, 'admin');
$router->get('admin/events/create',         'EventController@create',           true, 'admin');
$router->post('admin/events/store',         'EventController@store',            true, 'admin');
$router->get('admin/events/{id}/edit',      'EventController@edit',             true, 'admin');
$router->post('admin/events/{id}/update',   'EventController@update',           true, 'admin');
$router->post('admin/events/{id}/delete',   'EventController@delete',           true, 'admin');