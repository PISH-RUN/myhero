<?php


namespace App\Telegram\Handlers;


use App\Telegram\Callback\Callback;
use App\Telegram\Callback\UnknownCallback;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Telegram\Bot\Api;

class CallbackQueryHandler implements Handler
{
    protected string $namespace = "App\Telegram\Callback\\";

    public function __construct(public Api $telegram)
    {
    }

    public function handle(): void
    {
        $updates = $this->telegram->getWebhookUpdate();

        $data = Arr::get($updates, 'callback_query.data');

        [$callback, $value] = explode('.', $data);

        $this->handler($callback, $value);
    }

    public function handler($callback, $value)
    {
        $handler = $this->handlerName($callback);
        if (!class_exists($handler)) {
            $handler = UnknownCallback::class;
        }

        $concrete = app()->make($handler);

        if ($concrete instanceof Callback) {
            $concrete->handle($value);
        }

    }

    protected function handlerName(string $callback): string
    {
        return $this->namespace . Str::studly($callback) . "Callback";
    }
}
