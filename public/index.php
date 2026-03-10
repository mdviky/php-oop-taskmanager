<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Config;
use App\Core\Container;
use App\Core\Env;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\View;
use App\Controllers\TaskController;
use App\Controllers\HomeController;
use App\Core\Database;
use App\Repositories\TaskRepository;
use App\Repositories\HomeRepository;
use App\Services\TaskService;
use App\Services\HomeService;

Env::load(__DIR__ . '/../.env');
Config::load(__DIR__ . '/../config/app.php', 'app');
Config::load(__DIR__ . '/../config/db.php', 'db');

View::setBasePath(__DIR__ . '/../app/Views');

$request = Request::fromGlobals();
$response = new Response();

$container = new Container();
$container->set(Request::class, fn () => $request);
$container->set(Response::class, fn () => $response);
$container->set(\PDO::class, function () {
    $db = new Database(Config::get('db'));
    return $db->getConnection();
});
$container->set(TaskRepository::class, fn ($c) => new TaskRepository($c->get(\PDO::class)));
$container->set(TaskService::class, fn ($c) => new TaskService($c->get(TaskRepository::class)));
$container->set(TaskController::class, fn ($c) => new TaskController(
    $c->get(TaskService::class),
    $c->get(Request::class),
    $c->get(Response::class)
));


$container->set(HomeRepository::class, fn ($c) => new HomeRepository($c->get(\PDO::class)));
$container->set(HomeService::class, fn ($c) => new HomeService($c->get(HomeRepository::class)));
$container->set(HomeController::class, fn ($c) => new HomeController(
    $c->get(HomeService::class),
    $c->get(Request::class),
    $c->get(Response::class)
));


$router = new Router($container, $request, $response);
$routes = require __DIR__ . '/../config/routes.php';
$routes($router);

$router->dispatch();
