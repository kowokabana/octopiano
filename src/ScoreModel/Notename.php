<?php


namespace App\ScoreModel;


abstract class Notename
{
    const a = 'a';
    const as = 'a#';
    const ab = 'ab';
    const b = 'b';
    const bb = 'bb';
    const c = 'c';
    const cs = 'c#';
    const d = 'd';
    const ds = 'd#';
    const db = 'db';
    const e = 'e';
    const eb = 'eb';
    const fs = 'f#';
    const fb = 'fb';
    const g = 'g';
    const gs = 'gs';
    const gb = 'gb';

    const ls = array(
        'a' => 'a',
        'a#' => 'as',
        'ab' => 'ab');

    public static function get(string $notename): bool
    {
        $reflect = new \ReflectionClass(self::class);
        return $reflect->getConstant(self::ls[$notename]);
    }
}