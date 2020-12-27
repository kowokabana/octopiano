<?php


namespace App\ScoreModel;


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
            if(++$i === count($splitScript) || !empty($notename) && strpos(self::notenameCodes, $c)) {
                array_push($notes, new Note($notename, $shift, $octave));
                $notename = '';
                $shift = '';
                $octave = '';
                if(!$inBracket) {
                    array_push($noteVectors, new NoteVector($notes, $length . $lengthEx));
                    $length = '';
                    $lengthEx = '';
                }
            }
            elseif(strpos(self::bracketCodes, $c) === 0)
                $inBracket = true;
            elseif(strpos(self::bracketCodes, $c) === 1)
                $inBracket = false;
            elseif(strpos(self::notenameCodes, $c))
                $notename = $c;
            elseif(empty($shift) && strpos(self::shiftCodes, $c))
                $shift = $c;
            elseif(empty($octave) && strpos(self::octaveCodes, $c))
                $octave = $c;
            elseif($c === $octave)
                $octave .= $c;
            elseif($inBracket)
                continue;
            elseif(empty($length) && strpos(self::lengthCodes, $c))
                $length = $c;
            elseif($c === $length)
                $length .= $c;
            elseif($c === self::lengthExtender)
                $lengthEx .= $c;
        }

        return $noteVectors;
    }
}