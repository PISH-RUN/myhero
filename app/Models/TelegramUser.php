<?php

namespace App\Models;

use App\Utils\Mobile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property int $tid
 * @property string|null $first_name
 * @property string|null $username
 * @property boolean $is_bot
 * @property string|null phone_number
 */
class TelegramUser extends Model
{
    use HasFactory;

    public static ?TelegramUser $current = null;

    protected $guarded = ['id'];

    public static function loadUser(array $tUser)
    {
        return self::$current = TelegramUser::updateOrCreate([
            'tid' => $tUser['id']
        ], [
            'first_name' => Arr::get($tUser, 'first_name'),
            'is_bot' => Arr::get($tUser, 'is_bot', false),
            'username' => Arr::get($tUser, 'username')
        ]);
    }

    public static function current(): ?self
    {
        return self::$current;
    }

    public function updatePhoneNumber(string $phoneNumber)
    {
        $this->phone_number = Mobile::withPlus($phoneNumber);
        $this->save();
    }
}
