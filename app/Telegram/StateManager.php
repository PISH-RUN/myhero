<?php


namespace App\Telegram;


use App\Models\TelegramUser;
use Illuminate\Support\Facades\Cache;

class StateManager
{
    protected string $cacheKey;

    public function __construct(TelegramUser $user)
    {
        $this->cacheKey = sprintf("state.%s", $user->tid);
    }

    public static function user(TelegramUser $user)
    {
        return new self($user);
    }

    public function set($handler)
    {
        Cache::put($this->cacheKey, $handler);
    }

    public function clear()
    {
        Cache::forget($this->cacheKey);
    }

    public function get(): ?string
    {
        return Cache::get($this->cacheKey);
    }

    public function run(bool $clear = true): bool
    {
        $handler = $this->get();

        if (!class_exists($handler)) {
            return false;
        }

        $concrete = app()->make($handler);

        if (method_exists($concrete, "handle")) {
            $concrete->handle();
            $clear && $this->clear();
            return true;
        }

        return false;
    }
}
