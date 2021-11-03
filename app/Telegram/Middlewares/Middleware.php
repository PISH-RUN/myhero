<?php


namespace App\Telegram\Middlewares;


use Telegram\Bot\Objects\Update;

interface Middleware
{
    public function handle(Update $update, \Closure $next);
}
