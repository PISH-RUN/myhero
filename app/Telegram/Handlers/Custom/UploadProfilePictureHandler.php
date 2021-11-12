<?php


namespace App\Telegram\Handlers\Custom;


use App\Models\TelegramUser;
use App\Myhero\ShareService;
use App\Telegram\Handlers\Handler;
use App\Telegram\Traits\MessageMethod;
use App\Telegram\Traits\ShareResult;
use App\Telegram\Utils\SendShareResponse;
use Illuminate\Support\Arr;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class UploadProfilePictureHandler implements Handler
{
    use MessageMethod, ShareResult;

    public function __construct(public Api $telegram)
    {
    }


    public function handle(): void
    {
        $update = $this->telegram->getWebhookUpdate(false);

        $pictureId = $this->getPhoto($update);

        if (is_null($pictureId)) {
            $this->sendNoPhotoUploadedMessage();
            return;
        }

        $this->share($pictureId);
    }

    protected function getPhoto(Update $update): ?string
    {
        return Arr::get($update, 'message.photo.2.file_id');
    }

    protected function sendNoPhotoUploadedMessage()
    {
        $this->sendMessage(__("telegram.should_be_photo"));
        $this->sendMessage(__("telegram.repeat_share"));
    }
}
