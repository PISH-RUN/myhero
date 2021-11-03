<?php

namespace App\Telegram;

use App\Telegram\Handlers\Handler;
use App\Telegram\Handlers\UnknownHandler;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class UpdateHandler
{
    protected string $namespace = "App\Telegram\Handlers\\";

    public function __construct(public Api $telegram) {}

    public function run()
    {
        try {
            $updates = $this->telegram->getWebhookUpdate();
            $this->middleware($updates, function($updates) {
                $this->handle($updates);
            });
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }

    protected function middleware(Update $update, \Closure $next)
    {
        (new MiddlewareHandler())->handle($update, $next);
    }

    protected function handle($updates)
    {
        $handler = $this->handlerName($updates);

        if (!class_exists($handler)) {
            $handler = UnknownHandler::class;
        }

        $concrete = app()->make($handler);

        if ($concrete instanceof Handler) {
            $concrete->handle();
        }
    }

    protected function handlerName($updates): string
    {
        return $this->namespace . Str::studly(array_key_last($updates->toArray())) . "Handler";
    }

}
