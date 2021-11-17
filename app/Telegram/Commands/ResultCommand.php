<?php


namespace App\Telegram\Commands;

use App\Telegram\NeedRecommend;
use Telegram\Bot\Keyboard\Keyboard;

class ResultCommand extends Command
{
    use NeedRecommend;

    protected bool $runRecommends = false;

    public function shouldRunRecommend()
    {
        $this->runRecommends = true;
        return $this;
    }

    public function handle()
    {
        $type = $this->recommend()->getType();

        if (is_null($type)) {
            $this->sendSignupMessage();
            return;
        }

        if (!$type->avatar) {
            $this->sendMessage(__('telegram.result.description', [
                'description' => $type->description
            ]));
            return;
        }

        $message = $this->sendPhoto(
            $type->avatar,
            __('telegram.result.caption', [
                'title' => $type->title,
                'nickname' => $type->nickname
            ])
        );

        $this->sendMessage(__('telegram.result.description', [
            'description' => $type->description
        ]), [
            'reply_to_message_id' => $message->message_id
        ]);

        if ($this->runRecommends) {
            app()->make(RecommendCommand::class)->handle();
        }
    }

    protected function sendSignupMessage()
    {
        $this->sendMessage(__('telegram.choose_hero'), ['reply_markup' => $this->signupKeyboard()]);
    }

    protected function signupKeyboard()
    {
        return Keyboard::make([
            "inline_keyboard" => [
                [
                    ["text" => __('telegram.myhero_website'), 'url' => config('myhero.site')]
                ]
            ]
        ]);
    }
}
