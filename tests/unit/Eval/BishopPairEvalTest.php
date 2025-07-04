<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\BishopPairEval;
use Chess\Play\SanPlay;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class BishopPairEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function B25()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $B25 = file_get_contents(self::DATA_FOLDER.'/sample/B25.pgn');
        $board = (new SanPlay($B25))->validate()->board;
        $result = (new BishopPairEval($board))->result;

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function C68()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black has the bishop pair.",
        ];

        $C68 = file_get_contents(self::DATA_FOLDER.'/sample/C68.pgn');
        $board = (new SanPlay($C68))->validate()->board;
        $bishopPairEval = new BishopPairEval($board);

        $this->assertSame($expectedResult, $bishopPairEval->result);
        $this->assertSame($expectedExplanation, $bishopPairEval->explain());
    }

    /**
     * @test
     */
    public function B_B_vs_b_b()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $board = (new StrToBoard('8/5b2/4k3/4b3/8/8/1KBB4/8 w - -'))->create();
        $result = (new BishopPairEval($board))->result;

        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function B_B_vs_n_b()
    {
        $expectedResult = [
            'w' => 1,
            'b' => 0,
        ];

        $expectedExplanation = [
            "White has the bishop pair.",
        ];

        $board = (new StrToBoard('8/5n2/4k3/4b3/8/8/1KBB4/8 w - -'))->create();
        $bishopPairEval = new BishopPairEval($board);

        $this->assertSame($expectedResult, $bishopPairEval->result);
        $this->assertSame($expectedExplanation, $bishopPairEval->explain());
    }

    /**
     * @test
     */
    public function N_B_vs_b_b()
    {
        $expectedResult = [
            'w' => 0,
            'b' => 1,
        ];

        $expectedExplanation = [
            "Black has the bishop pair.",
        ];

        $board = (new StrToBoard('8/3k4/2bb4/8/8/4BN2/4K3/8 w - -'))->create();
        $bishopPairEval = new BishopPairEval($board);

        $this->assertSame($expectedResult, $bishopPairEval->result);
        $this->assertSame($expectedExplanation, $bishopPairEval->explain());
    }

    /**
     * @test
     */
    public function P_P_R_N_vs_q()
    {
        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $fen = '3k4/5RN1/4P3/5P2/7K/8/8/6q1 b - -';
        $board = (new StrToBoard($fen))->create();
        $result = (new BishopPairEval($board))->result;

        $this->assertSame($expected, $result);
    }
}
