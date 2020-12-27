<?php


namespace App\ScoreModel;


class Note
{
    private string $notename;
    private string $shift;
    private string $octave;

    public function __construct(string $notename, string $shift, string $octave)
    {
        $this->notename = $notename;
        $this->shift = $shift;
        $this->octave = $octave;
    }

    public function getNotename(): string
    {
        return $this->notename;
    }

    public function getShift(): string
    {
        return $this->shift;
    }

    public function getOctave(): string
    {
        return $this->octave;
    }
}