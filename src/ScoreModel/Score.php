<?php


namespace App\ScoreModel;


class Score
{
    private array $staffs;

    public function __construct()
    {
        $this->staffs = array();
    }

    public function Add(int $staffId, string $scoreScript): void
    {
        $scoreVectors = $this->decodeScoreScript($scoreScript);

        if(!array_key_exists($staffId, $this->staffs)) {
            array_push($this->staffs, [$staffId => $scoreVectors]);
        }
    }

    public function GetStaff(int $staffId): array
    {
        return $this->staffs[$staffId];
    }

    private function decodeScoreScript(string $scoreScript): array
    {
        $scoreScriptArray = str_split($scoreScript);
        $vecArray = array();
        foreach ($scoreScriptArray as $c)
        {
            $noteVector = new NoteVector(Notename::get($c),Length::h,Octave::five);
            array_push($vecArray, $noteVector);
        }
        return $vecArray;
    }
}