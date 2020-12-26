<?php


namespace App\ScoreModel;

class NoteVector
{
    private array $note;
    private string $length;

    private const notenameCodes = 'abcdefg';
    private const shiftCodes = '#*';
    private const octaveCodes = "',";
    private const lengthCodes = "-_";
    private const lengthExtender = ".";
    private const bracketCodes = "()";

    public function __constructor()
    {

    }

    public static function Create(string $scoreScript): NoteVector
    {
        // Example1: (c#''d*,,e)--.
        // Example2: c#'__.

        $notes = array();
        $notename = '';
        $shift = '';
        $octave = '';
        $inBracket = false;

        $length = '';
        $lengthEx = '';

        $s = str_split($scoreScript);
        foreach($s as $c) {
            if(empty($notename) && strpos($c, self::notenameCodes))
                $notename = $c;
            elseif(empty($shift) && strpos($c, self::shiftCodes))
                $shift = $c;
            elseif(empty($octave) && strpos($c, self::octaveCodes))
                $octave = $c;
            elseif($c == $octave)
                $octave .= $c;
            elseif(empty($length) && strpos($c, self::lengthCodes))
                $length = $c;
            elseif($c == $length)
                $length .= $c;
            elseif($c == self::lengthExtender)
                $lengthEx .= $c;
        }

        return new self();
    }
}