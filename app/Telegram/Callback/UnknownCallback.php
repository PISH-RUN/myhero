<?php


namespace App\Telegram\Callback;


use App\Models\TelegramUser;
use App\Myhero\Recommend;
use App\Telegram\NeedRecommend;
use Illuminate\Support\Arr;

class UnknownCallback extends Callback
{
    use NeedRecommend;

    public function handle($value)
    {
        $this->answerCallbackQuery(__("telegram.unknown_callback"), [
            'show_alert' => true
        ]);
    }
}
