<?php


namespace App\ScoreModel;

use App\Common\Util;


class Score
{
    private array $staffs;

    private const notenameCodes = 'abcdefg';
    private const shiftCodes = '#*';
    private const octaveCodes = "',";
    private const lengthCodes = "-_";
    private const lengthExtender = ".";
    private const bracketCodes = "()";

    public function __construct()
    {
        $this->staffs = array();
    }

    public function Append(int $staffId, string $scoreScript): void
    {
        $scoreVectors = self::Parse($scoreScript);

        if(!array_key_exists($staffId, $this->staffs)) {
            array_push($this->staffs, [$staffId => $scoreVectors]);
        } else {
            array_push($this->staffs[$staffId], $scoreVectors);
        }
    }

    /**
     * @param int $staffId
     * @return NoteVector[]
     */
    public function GetStaff(int $staffId): array
    {
        return $this->staffs[$staffId];
    }

    /**
     * Example1: (c#''d*,,e)--.gab
     * Example2: (c#''d*,,e)gab
     * Example3: c#'__.e
     * @param string $scoreScript
     * @return NoteVector[]
     */
    public static function Parse(string $scoreScript): array
    {
        $i = 0;
        $noteVectors = array(); #NoteVector[]

        $notes = array(); #Note[]
        $notename = '';
        $shift = '';
        $octave = '';
        $inBracket = false;

        $length = '';
        $lengthEx = '';

        $splitScript = str_split($scoreScript);
        foreach($splitScript as $c) {
            $i++;
            if(Util::str_contains(self::notenameCodes, $c)) {
                if(!empty($notename)) {
                    array_push($notes, new Note($notename, $shift, $octave));
                    $shift = '';
                    $octave = '';
                    if(!$inBracket) {
                        array_push($noteVectors, new NoteVector($notes, $length . $lengthEx));
                        $length = '';
                        $lengthEx = '';
                        $notes = array();
                    }
                }
                $notename = $c;
            }
            elseif(strpos(self::bracketCodes, $c) === 0)
                $inBracket = true;
            elseif(strpos(self::bracketCodes, $c) === 1)
                $inBracket = false;
            elseif(empty($shift) && Util::str_contains(self::shiftCodes, $c))
                $shift = $c;
            elseif(empty($octave) && Util::str_contains(self::octaveCodes, $c))
                $octave = $c;
            elseif($c === substr($octave, 0, 1))
                $octave .= $c;
            elseif($inBracket)
                continue;
            elseif(empty($length) && Util::str_contains(self::lengthCodes, $c))
                $length = $c;
            elseif($c === substr($length, 0, 1))
                $length .= $c;
            elseif($c === self::lengthExtender)
                $lengthEx .= $c;

            if($i === count($splitScript) || strpos(self::bracketCodes, $c) === 0) {
                if(empty($notename)) continue;
                array_push($notes, new Note($notename, $shift, $octave));
                $shift = '';
                $octave = '';
                $notename = '';
                array_push($noteVectors, new NoteVector($notes, $length . $lengthEx));
                $length = '';
                $lengthEx = '';
                $notes = array();
            }
        }
        return $noteVectors;
    }
}