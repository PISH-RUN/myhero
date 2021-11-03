<?php


namespace App\Telegram\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class RecommendCommand extends Command
{
    use ChoseHero;

    public function handle()
    {
        if (!$this->checkChoseHero()) {
            return;
        }

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
