<?php


namespace App\Utils;


class Mobile
{
    public static function local($phone) {
        return '0' . substr($phone, -10, 10);
    }
}
