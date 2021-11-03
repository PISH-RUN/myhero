<?php


namespace App\Myhero;


use App\Models\TelegramUser;
use App\Telegram\Models\Type;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;

class Recommend
{
    protected Service $myhero;
    protected TelegramUser $telegramUser;

    protected string $cacheKey;

    public function __construct(TelegramUser $telegramUser)
    {
        $this->myhero = new Service();
        $this->telegramUser = $telegramUser;
        $this->cacheKey = sprintf("myhero.recommends.%s", $telegramUser->phone_number);
    }

    public function get()
    {
        $recommends = $this->getFromCache();
        if (is_null($recommends)) {
            $recommends = $this->getRecommends();
        }

        return $recommends;
    }

    public function getType(): ?Type
    {
        $type = Arr::get($this->get(), 'type');

        if (is_null($type)) {
            return null;
        }

        return new Type($type);
    }

    protected function getFromCache(): mixed
    {
        return Cache::get($this->cacheKey);
    }

    protected function getRecommends()
    {
        if (is_null($this->telegramUser->phone_number)) {
            return;
        }

        $result = $this->myhero->getRecommends($this->telegramUser->phone_number);

        if (is_null($result)) {
            return null;
        }

        Cache::put($this->cacheKey, $result, now()->addDay());

        return $result;
    }
}
