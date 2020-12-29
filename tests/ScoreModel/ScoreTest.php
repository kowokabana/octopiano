<?php


namespace App\Tests\ScoreModel;


use App\ScoreModel\Score;
use PHPUnit\Framework\TestCase;

class ScoreTest extends TestCase
{
    public function test_parse_simple_script()
    {
        $script = "cdefgab";
        $sut = Score::Parse($script);

        $this->assertCount(7, $sut);
        $this->assertEquals('c', $sut[0]->getNotes()[0]->getNotename());
        $this->assertEquals('d', $sut[1]->getNotes()[0]->getNotename());
        $this->assertEquals('e', $sut[2]->getNotes()[0]->getNotename());
        $this->assertEquals('f', $sut[3]->getNotes()[0]->getNotename());
        $this->assertEquals('g', $sut[4]->getNotes()[0]->getNotename());
        $this->assertEquals('a', $sut[5]->getNotes()[0]->getNotename());
        $this->assertEquals('b', $sut[6]->getNotes()[0]->getNotename());
        $this->assertEquals('', $sut[0]->getLength());
    }

    public function test_parse_complex_script()
    {
        $script = "c'''d--.e___f#'--g*,-a''.b_";
        $sut = Score::Parse($script);

        $this->assertCount(7, $sut);
        $this->assertEquals('c', $sut[0]->getNotes()[0]->getNotename());
        $this->assertEquals("'''", $sut[0]->getNotes()[0]->getOctave());
        $this->assertEquals('', $sut[0]->getLength());
        $this->assertEquals('d', $sut[1]->getNotes()[0]->getNotename());
        $this->assertEquals('--.', $sut[1]->getLength());
        $this->assertEquals('e', $sut[2]->getNotes()[0]->getNotename());
        $this->assertEquals('___', $sut[2]->getLength());
        $this->assertEquals('f', $sut[3]->getNotes()[0]->getNotename());
        $this->assertEquals('#', $sut[3]->getNotes()[0]->getShift());
        $this->assertEquals("'", $sut[3]->getNotes()[0]->getOctave());
        $this->assertEquals('--', $sut[3]->getLength());
        $this->assertEquals('g', $sut[4]->getNotes()[0]->getNotename());
        $this->assertEquals('*', $sut[4]->getNotes()[0]->getShift());
        $this->assertEquals(',', $sut[4]->getNotes()[0]->getOctave());
        $this->assertEquals('-', $sut[4]->getLength());
        $this->assertEquals('a', $sut[5]->getNotes()[0]->getNotename());
        $this->assertEquals("''", $sut[5]->getNotes()[0]->getOctave());
        $this->assertEquals(".", $sut[5]->getLength());
        $this->assertEquals('b', $sut[6]->getNotes()[0]->getNotename());
        $this->assertEquals('_', $sut[6]->getLength());
    }

    public function test_parse_simple_chord_script()
    {
        $script = "(c#''d*,,e)--.g__a-.b";
        $sut = Score::Parse($script);

        $this->assertCount(4, $sut);
        $this->assertEquals('c', $sut[0]->getNotes()[0]->getNotename());
        $this->assertEquals('#', $sut[0]->getNotes()[0]->getShift());
        $this->assertEquals("''", $sut[0]->getNotes()[0]->getOctave());
        $this->assertEquals('d', $sut[0]->getNotes()[1]->getNotename());
        $this->assertEquals('*', $sut[0]->getNotes()[1]->getShift());
        $this->assertEquals(',,', $sut[0]->getNotes()[1]->getOctave());
        $this->assertEquals('e', $sut[0]->getNotes()[2]->getNotename());
        $this->assertEquals('', $sut[0]->getNotes()[2]->getShift());
        $this->assertEquals('', $sut[0]->getNotes()[2]->getOctave());
        $this->assertEquals('--.', $sut[0]->getLength());
        $this->assertEquals('g', $sut[1]->getNotes()[0]->getNotename());
        $this->assertEquals('__', $sut[1]->getLength());
        $this->assertEquals('a', $sut[2]->getNotes()[0]->getNotename());
        $this->assertEquals('-.', $sut[2]->getLength());
        $this->assertEquals('b', $sut[3]->getNotes()[0]->getNotename());
        $this->assertEquals('', $sut[3]->getNotes()[0]->getOctave());
        $this->assertEquals('', $sut[3]->getNotes()[0]->getShift());
        $this->assertEquals('', $sut[3]->getLength());
    }

    public function test_parse_complex_chord_script()
    {
        $script = "(c#''d*,,e)_.(gab)(cde)(f*'''g#a,,)";
        $sut = Score::Parse($script);

        $this->assertCount(4, $sut);
        $this->assertEquals('c', $sut[0]->getNotes()[0]->getNotename());
        $this->assertEquals('d', $sut[0]->getNotes()[1]->getNotename());
        $this->assertEquals('e', $sut[0]->getNotes()[2]->getNotename());
        $this->assertEquals('_.', $sut[0]->getLength());
        $this->assertEquals('g', $sut[1]->getNotes()[0]->getNotename());
        $this->assertEquals('a', $sut[1]->getNotes()[1]->getNotename());
        $this->assertEquals('b', $sut[1]->getNotes()[2]->getNotename());
        $this->assertEquals('', $sut[1]->getLength());
        $this->assertEquals('c', $sut[2]->getNotes()[0]->getNotename());
        $this->assertEquals('d', $sut[2]->getNotes()[1]->getNotename());
        $this->assertEquals('e', $sut[2]->getNotes()[2]->getNotename());
        $this->assertEquals('', $sut[2]->getLength());
        $this->assertEquals('f', $sut[3]->getNotes()[0]->getNotename());
        $this->assertEquals('*', $sut[3]->getNotes()[0]->getShift());
        $this->assertEquals("'''", $sut[3]->getNotes()[0]->getOctave());
        $this->assertEquals('g', $sut[3]->getNotes()[1]->getNotename());
        $this->assertEquals('#', $sut[3]->getNotes()[1]->getShift());
        $this->assertEquals('', $sut[3]->getNotes()[1]->getOctave());
        $this->assertEquals('a', $sut[3]->getNotes()[2]->getNotename());
        $this->assertEquals('', $sut[3]->getNotes()[2]->getShift());
        $this->assertEquals(',,', $sut[3]->getNotes()[2]->getOctave());
    }

    public function test_script_with_mistakes()
    {
        $script = "(c#''d*,,e)_.(gab)(c''de)(f*'''g#a,,)";
        $sut = Score::Parse($script);

        $this->assertCount(4, $sut);
    }
}