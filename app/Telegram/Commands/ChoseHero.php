<?php


namespace App\Telegram\Commands;

use App\Telegram\NeedRecommend;
use Telegram\Bot\Keyboard\Keyboard;

trait ChoseHero
{
    use NeedRecommend;

    public function checkChoseHero(): bool
    {
        $results = $this->recommend()->get();

        if (is_null($results)) {
            $this->sendMessage(__('telegram.choose_hero'), ['reply_markup' => $this->chooseHeroMarkup()]);
            return false;
        }

        return true;
    }

    protected function chooseHeroMarkup(): Keyboard
    {
        return Keyboard::make([
            'inline_keyboard' => [
                [
                    ['text' => __('telegram.myhero_website'), 'url' => config("myhero.site")]
                ]
            ]
        ]);
    }
}
