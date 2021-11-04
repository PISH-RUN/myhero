<?php


namespace App\Telegram\Commands;

use App\Models\TelegramUser;
use App\Telegram\NeedRecommend;
use Telegram\Bot\Keyboard\Keyboard;

class ShareCommand extends Command
{
    use NeedRecommend;

    public function handle()
    {
        $this->sendMessage(__("telegram.share"), [
            "reply_markup" => $this->keyboard()
        ]);
    }

    protected function keyboard(): Keyboard
    {
        return Keyboard::make([
            "inline_keyboard" => [
                [['text' => __("telegram.shares.profile_picture_button"), 'callback_data' => 'share.profile_picture']],
                [['text' => __("telegram.shares.upload_picture_button"), 'callback_data' => 'share.upload_picture']],
            ]
        ]);
    }


}
