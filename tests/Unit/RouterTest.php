<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testItMatchesRouteParameters(): void
    {
        $container = new Container();
        $request = new Request('GET', '/tasks/42', [], []);
        $response = new Response();

        $container->set(\Tests\Unit\TestController::class, fn () => new TestController($request, $response));

        $router = new Router($container, $request, $response);
        $router->get('/tasks/{id}', TestController::class . '@show');

        $router->dispatch();

        $this->assertSame('show:42', $response->getBody());
    }
}

final class TestController
{
    public function __construct(private Request $request, private Response $response)
    {
    }

    public function show(string $id): Response
    {
        return $this->response->setBody('show:' . $id);
    }
}
