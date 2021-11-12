<?php


namespace App\Telegram\Callback;


use App\Models\TelegramUser;
use App\Myhero\Recommend;
use App\Myhero\ShareService;
use App\Telegram\Handlers\Custom\UploadProfilePictureHandler;
use App\Telegram\NeedRecommend;
use App\Telegram\StateManager;
use App\Telegram\Traits\ShareResult;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ShareCallback extends Callback
{
    use NeedRecommend, ShareResult;

    public function handle($value)
    {
        $method = Str::camel($value);
        $this->answerCallbackQuery(__("telegram.share_selection"));

        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    protected function uploadPicture()
    {
        StateManager::user(TelegramUser::current())->set(UploadProfilePictureHandler::class);

        $this->sendUploadPictureMessage();
    }

    protected function profilePicture()
    {
        $pictureId = $this->getProfilePhotoId();

        if (is_null($pictureId)) {
            $this->sendAccessDeniedPictureMessage();
            return;
        }

        $this->share($pictureId);
    }

    protected function getProfilePhotoId(): ?string
    {
        $userProfilePhotos = $this->telegram->getUserProfilePhotos([
            "user_id" => TelegramUser::current()->tid,
            "limit" => 1
        ]);

        return Arr::get($userProfilePhotos, 'photos.0.2.file_id');
    }

    protected function sendUploadPictureMessage()
    {
        $this->sendMessage(__('telegram.shares.ask_upload_picture'));
    }

    protected function sendAccessDeniedPictureMessage()
    {
        $this->sendMessage(__('telegram.access_profile_picture_error'));
    }
}
