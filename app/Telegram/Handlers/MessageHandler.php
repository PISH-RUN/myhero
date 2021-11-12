<?php


namespace App\Telegram\Handlers;


use App\Telegram\Commands\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class MessageHandler implements Handler
{
    protected string $commandsNamespace = "App\Telegram\Commands\\";

    public function __construct(public Api $telegram)
    {
    }

    public function handle(): void
    {
        $updates = $this->telegram->getWebhookUpdate(false);

        $text = $this->getText($updates);

        if ($this->isCommand($text)) {
            $result = $this->handleCommand($text);
            if ($result) {
                return;
            }
        }

        $this->passToUnknownHandler();
    }

    protected function passToUnknownHandler()
    {
        app()->make(UnknownHandler::class)->handle();
    }

    protected function getText(Update $updates): ?string
    {
        return Arr::get($updates, 'message.text');
    }

    protected function isCommand(?string $text): bool
    {
        if (is_null($text)) {
            return false;
        }

        return Str::startsWith($text, "/");
    }

    protected function handleCommand($text): bool
    {
        $command = $this->commandClassName($text);

        if (!class_exists($command)) {
            return false;
        }

        $concrete = app()->make($command);

        if (!($concrete instanceof Command)) {
            return false;
        }

        $concrete->handle();
        return true;
    }

    protected function commandClassName($text): string
    {
        $command = substr($text, 1);

        return $this->commandsNamespace . Str::studly($command) . "Command";
    }
}
