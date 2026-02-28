<?php

$router->get('', 'HomeController@index');
$router->get('flash-test', 'HomeController@triggerFlash');
$router->get('login', 'LoginController@show');
$router->post('login', 'LoginController@login');
$router->get('logout', 'LoginController@logout');
$router->get('admin', 'AdminController@index', true, 'admin');
$router->get('admin/posts', 'PostController@index');
$router->get('admin/posts/create', 'PostController@create');
$router->post('admin/posts/store', 'PostController@store');