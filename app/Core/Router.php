<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function __construct(
        private Container $container,
        private Request $request,
        private Response $response
    ) {
    }

    public function get(string $path, string $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, string $handler): void
    {
        $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $paramNames = [];
        if (preg_match_all('#\{([^/]+)\}#', $path, $matches)) {
            $paramNames = $matches[1];
        }

        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'params' => $paramNames,
        ];
    }

    public function dispatch(): void
    {
        $method = $this->request->method();
        $path = $this->request->path();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            array_shift($matches);
            $params = [];
            foreach ($route['params'] as $index => $name) {
                $params[$name] = $matches[$index] ?? null;
            }

            /* $this->invokeHandler($route['handler'], $params);
            return; */
            // after building $params...
            $this->request = $this->request->withRouteParams($params);
            $this->invokeHandler($route['handler'], $params);
            return;
        }

        $this->response
            ->setStatus(404)
            ->setBody('Not Found')
            ->send();
    }

    private function invokeHandler(string $handler, array $params): void
    {
        [$class, $method] = explode('@', $handler);
        $controller = $this->container->get($class);

        $result = $controller->{$method}(...array_values($params));

        if ($result instanceof Response) {
            $result->send();
            return;
        }

        $this->response->setBody((string) $result)->send();
    }
}
