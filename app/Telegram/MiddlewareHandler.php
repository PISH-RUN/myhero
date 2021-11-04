<?php


namespace App\Telegram;


use App\Telegram\Middlewares\CheckPhoneNumberMiddleware;
use App\Telegram\Middlewares\Middleware;
use App\Telegram\Middlewares\SavePhoneNumberMiddleware;
use App\Telegram\Middlewares\StateMiddleware;
use App\Telegram\Middlewares\UserMiddleware;
use App\Telegram\Middlewares\WelcomeMiddleware;
use Closure;
use Illuminate\Support\Collection;
use Telegram\Bot\Objects\Update;

class MiddlewareHandler
{
    protected array $middlewares = [
        UserMiddleware::class,
        WelcomeMiddleware::class,
        SavePhoneNumberMiddleware::class,
        CheckPhoneNumberMiddleware::class,
        StateMiddleware::class
    ];

    protected ?Collection $loadedMiddleware = null;
    protected Closure $next;

    public function __construct()
    {
        $this->loadMiddleware();
    }

    protected function loadMiddleware()
    {
        $this->loadedMiddleware = collect($this->middlewares)->reverse();
    }

    public function handle(Update $update, Closure $next)
    {
        $this->next = $next;
        $this->handleMiddleware($update, $this->nextMiddleware());
    }

    protected function handleMiddleware(Update $update, ?string $middleware)
    {
        if (is_null($middleware)) {
            $next = $this->next;
            return $next($update);
        }

        $this->resolve($middleware)->handle($update, function ($update) {
            $this->handleMiddleware($update, $this->nextMiddleware());
        });
    }

    protected function resolve(string $middleware): Middleware
    {
        return app()->make($middleware);
    }

    protected function nextMiddleware(): ?string
    {
        return $this->loadedMiddleware->pop();
    }
}
