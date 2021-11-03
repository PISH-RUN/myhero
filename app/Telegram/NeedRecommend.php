<?php


namespace App\Telegram;


use App\Models\TelegramUser;
use App\Myhero\Recommend;

trait NeedRecommend
{
    protected ?Recommend $recommend = null;

    protected function recommend(): Recommend
    {
        return $this->recommend ?? $this->recommend = new Recommend(TelegramUser::current());
    }
}
