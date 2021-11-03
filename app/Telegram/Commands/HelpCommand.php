<?php


namespace App\Telegram\Commands;

class HelpCommand extends Command
{

    public function handle()
    {
        $this->sendMessage(__('telegram.help'));
    }
}
