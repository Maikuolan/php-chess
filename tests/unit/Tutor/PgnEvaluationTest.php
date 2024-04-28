<?php

namespace Chess\Tests\Unit\Tutor;

use Chess\FenToBoardFactory;
use Chess\Play\SanPlay;
use Chess\Tutor\PgnEvaluation;
use Chess\Tests\AbstractUnitTestCase;

class PgnEvaluationTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function A08()
    {
        $expected = [
            "Black has some space advantage.",
            "White has a small protection advantage.",
            "The pawn on c5 is unprotected.",
            "Overall, 2 heuristic evaluation features are favoring White while 2 are favoring Black.",
        ];

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');
        $board = (new SanPlay($A08))->validate()->getBoard();

        $paragraph = (new PgnEvaluation('d4', $board))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }

    /**
     * @test
     */
    public function endgame()
    {
        $expected = [
            "White has a decisive material advantage.",
            "White is totally controlling the center.",
            "White has a total space advantage.",
            "The white pieces are timidly approaching the other side's king.",
            "Black has a significant protection advantage.",
            "The bishop on e6 is unprotected.",
            "Overall, 6 heuristic evaluation features are favoring White while 1 is favoring Black.",
        ];

        $board = FenToBoardFactory::create('8/5k2/4n3/8/8/1BK5/1B6/8 w - - 0 1');

        $paragraph = (new PgnEvaluation('Bxe6+', $board))->getParagraph();

        $this->assertSame($expected, $paragraph);
    }
}