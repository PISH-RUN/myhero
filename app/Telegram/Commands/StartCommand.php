<?php


namespace App\Telegram\Commands;


class StartCommand extends Command
{
    public function handle()
    {
        $this->sendMessage(__('telegram.start'));
    }
}
