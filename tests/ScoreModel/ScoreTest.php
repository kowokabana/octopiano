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
        $this->assertEquals('c', $sut[0]->getNotes()[0]);
        $this->assertEquals('g', $sut[4]->getNotes()[0]);
        $this->assertEquals('b', $sut[6]->getNotes()[0]);
        $this->assertEquals('', $sut[0]->getLength());
    }

    public function testChordScript()
    {
        $script1 = "(c#''d*,,e)--.gab";
    }
}