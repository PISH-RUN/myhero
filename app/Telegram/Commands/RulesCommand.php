<?php


namespace App\Telegram\Commands;


class RulesCommand extends Command
{

    public function handle()
    {
        $this->sendMessage(__('telegram.rules'));
    }
}
