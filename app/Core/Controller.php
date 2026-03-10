<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    public function __construct(
        protected Request $request,
        protected Response $response
    ) {
    }

    protected function view(string $template, array $data = []): string
    {
        return View::render($template, $data);
    }

    protected function redirect(string $path): Response
    {
        return $this->response->setStatus(302)->setHeader('Location', $path);
    }
}
