<?php


namespace App\Utils;


use Illuminate\Support\Str;

class Mobile
{
    public static function local($phone) {
        return '0' . substr($phone, -10, 10);
    }

    public static function international($phone)
    {
        return '+98' .  substr($phone, -10, 10);
    }

    public static function withPlus($phone)
    {
        return Str::start($phone, '+');
    }
}
