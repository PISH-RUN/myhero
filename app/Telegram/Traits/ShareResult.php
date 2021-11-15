<?php

namespace App\Telegram\Traits;

use App\Models\TelegramUser;
use App\Myhero\ShareService;
use Illuminate\Support\Arr;

trait ShareResult
{

    protected function share(string $pictureId)
    {
        $result = ShareService::user(TelegramUser::current())->upload($pictureId);
        dump($result);
        if (is_null($result)) {
            $this->sendMessage(__("telegram.error"));
            return;
        }

        if ($image = Arr::get($result, 'rectangle_image')) {
            $this->sendPhoto($image);
        }

        if ($image = Arr::get($result, 'square_image')) {
            $this->sendPhoto($image);
        }

        $this->sendVideoResult(Arr::get($result, 'video'));

    }

    protected function sendVideoResult(?array $videoData): void
    {
        if (!$videoData) {
            return;
        }

        if (!Arr::get($videoData, 'is_downloadable')) {
            $this->sendMessage(__("telegram.video_is_processing"));
            return;
        }

        if ($url = Arr::get($videoData, 'url')) {
            $this->sendVideo($url);
        }
    }

}
