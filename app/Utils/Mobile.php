<?php


namespace App\Utils;


class Mobile
{
    public static function local($phone) {
        return '0' . substr($phone, -10, 10);
    }

    public static function international($phone)
    {
        return '+98' .  substr($phone, -10, 10);
    }
}
