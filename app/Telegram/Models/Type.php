<?php


namespace App\Telegram\Models;


use Illuminate\Support\Arr;

class Type
{
    public string $title;

    public ?string $nickname;

    public ?string $description;

    public ?string $avatar;

    public ?string $hat;

    public function __construct(array $type)
    {
        $this->title = Arr::get($type, 'title');
        $this->nickname = Arr::get($type, 'nikname');
        $this->description = Arr::get($type, 'description');
        $this->avatar = Arr::get($type, 'avatar');
        $this->hat = Arr::get($type, 'hat');
    }
}
