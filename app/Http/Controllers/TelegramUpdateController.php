<?php

namespace App\Http\Controllers;

use App\Telegram\UpdateHandler;

class TelegramUpdateController extends Controller
{

    public function __construct(public UpdateHandler $updateHandler) {}

    public function __invoke(): string
    {
        $this->updateHandler->run();

        return 'ok';
    }
}
