<?php


namespace App\Myhero;


use App\Exceptions\Telegram\FilePathNullException;
use App\Models\TelegramUser;
use Illuminate\Support\Arr;
use Telegram\Bot\Laravel\Facades\Telegram;

class ShareService
{
    protected Service $myhero;

    protected TelegramUser $telegramUser;

    public function __construct(TelegramUser $telegramUser)
    {
        $this->myhero = new Service();
        $this->telegramUser = $telegramUser;
    }

    public static function user(TelegramUser $telegramUser)
    {
        return new self($telegramUser);
    }

    /**
     * @param string $fileId
     * @return array|null
     * @throws FilePathNullException
     */
    public function upload(string $fileId): ?array
    {
        $url = $this->url($fileId);
        $response = $this->myhero->postSharePicture($this->telegramUser->phone_number, $url);
        dump($response);
        return Arr::get($response, 'result.contents');
    }

    /**
     * @param string $fileId
     * @return string|null
     * @throws FilePathNullException
     */
    protected function url(string $fileId): ?string
    {
        $result = Telegram::getFile(["file_id" => $fileId]);

        $filePath = Arr::get($result, 'file_path');

        if (is_null($filePath)) {
            throw new FilePathNullException();
        }

        return sprintf("https://api.telegram.org/file/bot%s/%s", $this->token(), $filePath);
    }

    protected function token()
    {
        return config('telegram.bots.myhero.token');
    }
}
