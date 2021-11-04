<?php


namespace App\Telegram\Callback;


use App\Models\TelegramUser;
use App\Myhero\Recommend;
use App\Telegram\NeedRecommend;
use Illuminate\Support\Arr;

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

    protected function sendPhotoIfExists(string $text, ?string $photo)
    {
        if (is_null($photo)) {
            $this->sendMessage($text);
            return;
        }

        $this->sendPhoto($photo, $text);
    }

    protected function films($recommends)
    {
        foreach ($recommends as $recommend) {
            $name = Arr::get($recommend, 'name');
            $genre = Arr::get($recommend, 'genre');
            $poster = Arr::get($recommend, 'poster');

            $text = __(
                'telegram.recommends.film',
                ['name' => $name, 'genre' => $genre]
            );

            $this->sendPhotoIfExists($text, $poster);
        }
    }

    protected function books($recommends)
    {

        foreach ($recommends as $recommend) {
            $name = Arr::get($recommend, 'name');
            $writer = Arr::get($recommend, 'writer');
            $pic = Arr::get($recommend, 'pic');

            $text = __(
                'telegram.recommends.book',
                ['name' => $name, 'writer' => $writer]
            );

            $this->sendPhotoIfExists($text, $pic);
        }
    }

    protected function musics($recommends)
    {
        foreach ($recommends as $recommend) {
            $name = Arr::get($recommend, 'name');
            $genre = Arr::get($recommend, 'genre');
            $singer = Arr::get($recommend, 'singer');

            $text = __(
                'telegram.recommends.music',
                ['name' => $name, 'genre' => $genre, 'singer' => $singer]
            );

            $this->sendMessage($text);
        }
    }

    protected function podcasts($recommends)
    {
        foreach ($recommends as $recommend) {
            $name = Arr::get($recommend, 'name');
            $genre = Arr::get($recommend, 'genre');
            $teller = Arr::get($recommend, 'teller');

            $text = __(
                'telegram.recommends.podcast',
                ['name' => $name, 'genre' => $genre, 'teller' => $teller]
            );

            $this->sendMessage($text);
        }
    }

    public function advices($recommends)
    {
        foreach ($recommends as $recommend) {
            $title = Arr::get($recommend, 'name');
            $subject = Arr::get($recommend, 'genre');
            $description = Arr::get($recommend, 'singer');
            $attractiveness = Arr::get($recommend, 'attractiveness');

            $text = __(
                'telegram.recommends.advice',
                ['title' => $title, 'subject' => $subject, 'description' => $description, 'attractiveness' => $attractiveness]
            );

            $this->sendMessage($text);
        }
    }
}
