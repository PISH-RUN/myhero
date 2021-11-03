<?php


namespace App\Telegram\Commands;

use Telegram\Bot\Keyboard\Keyboard;

class ResultCommand extends Command
{
    use ChoseHero;

    public function handle()
    {
        if (!$this->checkChoseHero()) {
            return;
        }

        $type = $this->recommend()->getType();

        $type->avatar = "https://source.unsplash.com/random";

        $message = $this->sendPhoto(
            $type->avatar,
            __('telegram.result.caption', [
                'title' => $type->title,
                'nickname' => $type->nickname,
                'description' => $type->description
            ]),
            ['reply_markup' => $this->removeKeyboard()]
        );

        $this->sendMessage(__('telegram.result.result', [
            'description' => $type->description
        ]), [
            'reply_to_message_id' => $message->message_id
        ]);
    }

    protected function removeKeyboard(): Keyboard
    {
        return Keyboard::make([
            'remove_keyboard' => true
        ]);
    }
}
