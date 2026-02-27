<?php

declare(strict_types=1);

session_start();

/*
|--------------------------------------------------------------------------
| Autoload
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/../app/Core/Autoloader.php';
App\Core\Autoloader::register();

/*
|--------------------------------------------------------------------------
| Environment
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

/*
|--------------------------------------------------------------------------
| Router
|--------------------------------------------------------------------------
*/
use App\Core\Router;

$router = new Router();

require_once __DIR__ . '/../config/routes.php';

$router->dispatch($_GET['url'] ?? '/');