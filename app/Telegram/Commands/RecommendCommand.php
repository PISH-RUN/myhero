<?php


namespace App\Telegram\Commands;

use App\Telegram\NeedRecommend;
use Telegram\Bot\Keyboard\Keyboard;

class RecommendCommand extends Command
{
    use NeedRecommend;

    public function handle()
    {
        $this->sendMessage(__('telegram.recommend'), ['reply_markup' => $this->recommendReplyMarkup()]);
    }

    protected function recommendReplyMarkup(): Keyboard
    {
        return Keyboard::make([
            'inline_keyboard' => [
                [
                    ['text' => __('telegram.films'), 'callback_data' => 'recommend.films'],
                    ['text' => __('telegram.books'), 'callback_data' => 'recommend.books'],
                ],
                [
                    ['text' => __('telegram.musics'), 'callback_data' => 'recommend.musics'],
                    ['text' => __('telegram.podcasts'), 'callback_data' => 'recommend.podcasts']
                ],
                [
                    ['text' => __('telegram.advices'), 'callback_data' => 'recommend.advices']
                ]
            ]
        ]);
    }
}
