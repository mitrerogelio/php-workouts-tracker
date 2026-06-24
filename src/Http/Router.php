<?php

declare(strict_types=1);

namespace App\Http;

/**
 * Minimal front-controller router: maps "METHOD /path" to a handler closure.
 * Paths are matched exactly; use query strings for parameters (e.g. ?id=5).
 */
class Router
{
    /** @var array<string, callable(): void> */
    private array $routes = [];

    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[strtoupper($method) . ' ' . $path] = $handler;
    }

    public function dispatch(string $method, string $path): void
    {
        $handler = $this->routes[strtoupper($method) . ' ' . $path] ?? null;

        if ($handler === null) {
            http_response_code(404);
            View::render('error', [
                'title' => 'Page not found',
                'message' => "No route matches {$method} {$path}.",
            ]);
            return;
        }

        $handler();
    }
}
