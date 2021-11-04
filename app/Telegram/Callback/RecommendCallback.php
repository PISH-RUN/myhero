<?php


namespace App\Telegram\Callback;


use App\Models\TelegramUser;
use App\Myhero\Recommend;
use App\Telegram\NeedRecommend;
use Illuminate\Support\Arr;
use Telegram\Bot\Keyboard\Keyboard;

class RecommendCallback extends Callback
{
    use NeedRecommend;

    public function handle($value)
    {
        $this->answerCallbackQuery(__("telegram.recommend_selection"));

        $recommends = $this->recommend()->get()[$value];
        if (method_exists($this, $value)) {
            if (count($recommends) === 0) {
                $this->sendThereIsNoRecommendation();
                return;
            }

            $this->$value(Arr::random($recommends, 3));
        }
    }

    protected function sendThereIsNoRecommendation()
    {
        $this->sendMessage(__('telegram.no_recommend'));
    }

    public function advices($recommends)
    {
        foreach ($recommends as $recommend) {
            $title = Arr::get($recommend, 'name');
            $subject = Arr::get($recommend, 'genre');
            $description = Arr::get($recommend, 'singer');
            $attractiveness = Arr::get($recommend, 'attractiveness');
            $url = Arr::get($recommend, 'url');

            $text = __(
                'telegram.recommends.advice',
                ['title' => $title, 'subject' => $subject, 'description' => $description, 'attractiveness' => $attractiveness]
            );

            $this->sendMessageWithUrl($text, $url);
        }
    }

    public function sendMessageWithUrl(string $text, ?string $url)
    {
        $params = [];

        if (!is_null($url)) {
            $params = [
                'reply_markup' => $this->keyboardUrl(__('text.recommends.url'), $url)
            ];
        }

        $this->sendMessage($text, $params);
    }

    protected function keyboardUrl(string $urlText, string $url)
    {
        return Keyboard::make([
            'inline_keyboard' => [
                [['text' => $urlText, 'url' => $url]]
            ]
        ]);
    }

    protected function films($recommends)
    {
        foreach ($recommends as $recommend) {
            $name = Arr::get($recommend, 'name');
            $genre = Arr::get($recommend, 'genre');
            $poster = Arr::get($recommend, 'poster');
            $url = Arr::get($recommend, 'url');

            $text = __(
                'telegram.recommends.film',
                ['name' => $name, 'genre' => $genre]
            );

            $this->sendPhotoIfExists($text, $poster, $url);
        }
    }

    protected function sendPhotoIfExists(string $text, ?string $photo, ?string $url)
    {
        if (is_null($photo)) {
            $this->sendMessageWithUrl($text, $url);
            return;
        }

        $this->sendPhotoWithUrl($photo, $text, $url);
    }

    public function sendPhotoWithUrl(string $photo, string $text, ?string $url)
    {
        $params = [];

        if (!is_null($url)) {
            $params = [
                'reply_markup' => $this->keyboardUrl(__('text.recommends.url'), $url)
            ];
        }

        $this->sendPhoto($photo, $text, $params);
    }

    protected
    function books($recommends)
    {

        foreach ($recommends as $recommend) {
            $name = Arr::get($recommend, 'name');
            $writer = Arr::get($recommend, 'writer');
            $pic = Arr::get($recommend, 'pic');
            $url = Arr::get($recommend, 'url');

            $text = __(
                'telegram.recommends.book',
                ['name' => $name, 'writer' => $writer]
            );

            $this->sendPhotoIfExists($text, $pic, $url);
        }
    }

    protected
    function musics($recommends)
    {
        foreach ($recommends as $recommend) {
            $name = Arr::get($recommend, 'name');
            $genre = Arr::get($recommend, 'genre');
            $singer = Arr::get($recommend, 'singer');
            $url = Arr::get($recommend, 'url');

            $text = __(
                'telegram.recommends.music',
                ['name' => $name, 'genre' => $genre, 'singer' => $singer]
            );

            $this->sendMessageWithUrl($text, $url);
        }
    }

    protected
    function podcasts($recommends)
    {
        foreach ($recommends as $recommend) {
            $name = Arr::get($recommend, 'name');
            $genre = Arr::get($recommend, 'genre');
            $teller = Arr::get($recommend, 'teller');
            $url = Arr::get($recommend, 'url');

            $text = __(
                'telegram.recommends.podcast',
                ['name' => $name, 'genre' => $genre, 'teller' => $teller]
            );

            $this->sendMessageWithUrl($text, $url);
        }
    }
}
