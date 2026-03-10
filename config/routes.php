<?php

declare(strict_types=1);

use App\Core\Router;
use App\Controllers\TaskController;
use App\Controllers\HomeController;

return function (Router $router): void {
    $router->get('/tasks', TaskController::class . '@index');
    $router->get('/tasks/create', TaskController::class . '@create');
    $router->post('/tasks', TaskController::class . '@store');
    $router->get('/tasks/{id}', TaskController::class . '@show');
    $router->get('/tasks/{id}/edit', TaskController::class . '@edit');
    $router->post('/tasks/{id}', TaskController::class . '@update');
    $router->post('/tasks/{id}/delete', TaskController::class . '@delete');

    $router->get('/', HomeController::class . '@index');

/*     $router->get('/homes', HomeController::class . '@index');
    $router->get('/homes/create', HomeController::class . '@create');
    $router->post('/homes', HomeController::class . '@store');
    $router->get('/homes/{id}', HomeController::class . '@show');
    $router->get('/homes/{id}/edit', HomeController::class . '@edit');
    $router->post('/homes/{id}', HomeController::class . '@update');
    $router->post('/homes/{id}/delete', HomeController::class . '@delete'); */
};
