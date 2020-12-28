<?php


namespace App\Common;

class Util
{
    public static function str_contains(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }
}

