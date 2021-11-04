<?php

namespace App\Http\Controllers;

use App\Telegram\UpdateHandler;

class TelegramUpdateController extends Controller
{

    public function __construct(public UpdateHandler $updateHandler) {}

    public function __invoke(): string
    {
        if (config('myhero.disable')) {
            return 'ok';
        }

        $this->updateHandler->run();

        return 'ok';
    }
}
