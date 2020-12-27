<?php


namespace App\ScoreModel;

class NoteVector
{
    private array $notes;
    private string $length;

    public function __construct(array $notes, string $length)
    {
        $this->notes = $notes;
        $this->length = $length;
    }

    public function getNotes(): array
    {
        return $this->notes;
    }

    public function getLength(): string
    {
        return $this->length;
    }
}