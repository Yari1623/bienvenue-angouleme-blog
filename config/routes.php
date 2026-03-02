<?php

$router->get('', 'HomeController@index');
$router->get('flash-test', 'HomeController@triggerFlash');
$router->get('login', 'LoginController@show');
$router->post('login', 'LoginController@login');
$router->get('logout', 'LoginController@logout');
$router->get('admin', 'AdminController@index', true, 'admin');
$router->get('admin/posts', 'PostController@index', true, 'admin');
$router->get('admin/posts/create', 'PostController@create', true, 'admin');
$router->post('admin/posts/store', 'PostController@store', true, 'admin');
$router->get('article/{slug}', 'PostController@show');
$router->post('admin/posts/{id}/status', 'PostController@toggleStatus', true, 'admin');
$router->get('admin/posts/{id}/edit', 'PostController@edit', true, 'admin');
$router->post('admin/posts/{id}/update', 'PostController@update', true, 'admin');
$router->post('admin/posts/{id}/delete', 'PostController@delete', true, 'admin');
$router->get('/register', 'RegisterController@show');
$router->post('/register', 'RegisterController@register');
$router->post('/article/{slug}/comment', 'ArticleController@comment');
$router->post('/article/{slug}/comment', 'PostController@comment');
