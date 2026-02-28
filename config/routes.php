<?php

$router->get('', 'HomeController@index');
$router->get('flash-test', 'HomeController@triggerFlash');
$router->get('login', 'LoginController@show');
$router->post('login', 'LoginController@login');
$router->get('logout', 'LoginController@logout');