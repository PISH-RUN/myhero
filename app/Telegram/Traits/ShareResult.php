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

        if (is_null($result)) {
            $this->sendMessage(__("telegram.error"));
            return;
        }

        if ($image = Arr::get($result, 'image')) {
            $this->sendPhoto($image);
        }

        if ($video = Arr::get($result, 'video')) {
            $this->sendVideo($video);
        }
    }

}
