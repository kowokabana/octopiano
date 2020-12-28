<?php


namespace App\Tests\ScoreModel;


use App\ScoreModel\Score;
use PHPUnit\Framework\TestCase;

class ScoreTest extends TestCase
{
    public function testPHP()
    {
        $pos = strpos('aec', 'd');
        $this->assertEquals(5, $pos);
    }

    public function testParseSimpleScript()
    {
        $script = "cdefgab";
        $sut = Score::Parse($script);

        $this->assertCount(7, $sut);
        $this->assertEquals('c', $sut[0]->getNotes()[0]->getNotename());
        $this->assertEquals('g', $sut[4]->getNotes()[0]->getNotename());
        $this->assertEquals('a', $sut[5]->getNotes()[0]->getNotename());
        $this->assertEquals('b', $sut[6]->getNotes()[0]->getNotename());
        $this->assertEquals('', $sut[0]->getLength());
    }

    public function testChordScript()
    {
        $script = "(c#''d*,,e)--.g__a-.b";
        $sut = Score::Parse($script);

        $this->assertCount(4, $sut);
        $this->assertEquals('c', $sut[0]->getNotes()[0]->getNotename());
        $this->assertEquals('d', $sut[0]->getNotes()[1]->getNotename());
        $this->assertEquals('e', $sut[0]->getNotes()[2]->getNotename());
        $this->assertEquals('--.', $sut[0]->getLength());
        $this->assertEquals('g', $sut[1]->getNotes()[0]->getNotename());
        $this->assertEquals('__', $sut[1]->getLength());
    }
}