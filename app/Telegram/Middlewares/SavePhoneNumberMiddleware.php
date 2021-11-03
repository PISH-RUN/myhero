<?php

namespace App\Telegram\Middlewares;

use App\Models\TelegramUser;
use App\Telegram\Commands\ResultCommand;
use App\Telegram\Traits\ChatId;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class SavePhoneNumberMiddleware implements Middleware
{
    use ChatId;

    public function __construct(public Api $telegram)
    {
    }


    public function handle(Update $update, Closure $next)
    {
        if ($this->sharingPhoneNumber($update)) {
            return false;
        }

        return $next($update);
    }

    public function getContact(Update $update): ?array
    {
        return Arr::get($update, 'message.contact');
    }

    protected function checkSharedContactIsOwner(TelegramUser $user, array $contact): bool
    {
        $userId = Arr::get($contact, 'user_id');

        return $user->tid == $userId;
    }

    public function sharingPhoneNumber(Update $update): bool
    {
        $contact = $this->getContact($update);

        if (is_null($contact)) {
            return false;
        }

        $user = TelegramUser::current();

        if (!$this->checkSharedContactIsOwner($user, $contact)) {
            $this->sendContactOwnershipWarning($update);
        } else {
            $this->setPhoneNumber($user, $contact);
            $this->showResult();
        }

        return true;
    }

    protected function setPhoneNumber(TelegramUser $user, array $contact)
    {
        $user->updatePhoneNumber($contact['phone_number']);
    }

    protected function sendContactOwnershipWarning(Update $update)
    {
        $this->telegram->sendMessage([
            'chat_id' => $this->chatId($update),
            'text' => __('telegram.contact_ownership_warning')
        ]);
    }

    protected function showResult(): void
    {
        app()->make(ResultCommand::class)->handle();
    }
}
