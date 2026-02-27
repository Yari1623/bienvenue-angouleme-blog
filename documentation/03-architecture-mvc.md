# 03 – Architecture MVC

Le projet repose sur une architecture MVC personnalisée.

## Modèle (Model)
Responsable :
- des requêtes SQL
- de l’accès aux données
- de la logique métier

Exemple :
Post.php
User.php
Comment.php

## Vue (View)
Responsable :
- de l’affichage
- du rendu HTML
- aucune requête SQL

## Contrôleur (Controller)
Responsable :
- de recevoir la requête
- d’appeler le modèle
- de transmettre les données à la vue

## Front Controller

Un fichier unique :
public/index.php

Il centralise toutes les requêtes entrantes.

## Router personnalisé

Un routeur permet d’associer :
- une URL
- à un contrôleur
- et une méthode spécifique