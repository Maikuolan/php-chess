<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Eval\AbsolutePinEval;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class AbsolutePinEvalTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense()
    {
        $board = (new StrToBoard('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -'))
            ->create();

        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedElaboration = [
            "The knight on c6 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check.",
        ];

        $absPinEval = new AbsolutePinEval($board);

        $this->assertSame($expectedResult, $absPinEval->result);
        $this->assertSame($expectedElaboration, $absPinEval->elaborate());
    }

    /**
     * @test
     */
    public function c62_ruy_lopez_steinitz_defense_center_gambit_Bg4()
    {
        $board = (new StrToBoard('r2qkbnr/ppp2ppp/2np4/1B2p3/3PP1b1/5N2/PPP2PPP/RNBQK2R w KQkq -'))
            ->create();

        $expectedResult = [
            'w' => 0,
            'b' => 3.2,
        ];

        $expectedElaboration = [
            "The knight on c6 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check.",
        ];

        $absPinEval = new AbsolutePinEval($board);

        $this->assertSame($expectedResult, $absPinEval->result);
        $this->assertSame($expectedElaboration, $absPinEval->elaborate());
    }

    /**
     * @test
     */
    public function both_knights_pinned()
    {
        $board = (new StrToBoard('r2qk1nr/ppp2ppp/2n5/1B1pp3/1b1PP1b1/2N1BN2/PPP2PPP/R2QK2R w KQkq -'))
            ->create();

        $expectedResult = [
            'w' => 3.2,
            'b' => 3.2,
        ];

        $expectedElaboration = [
            "The knight on c6 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check.",
            "The knight on c3 is pinned shielding the king so it cannot move out of the line of attack because the king would be put in check.",
        ];

        $absPinEval = new AbsolutePinEval($board);

        $this->assertSame($expectedResult, $absPinEval->result);
        $this->assertSame($expectedElaboration, $absPinEval->elaborate());
    }

    /**
     * @test
     */
    public function endgame_Qg5_check()
    {
        $board = (new StrToBoard('1r4k1/7p/pqb5/3p2Q1/6P1/1PP4P/6B1/5R1K b - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $absPinEval = new AbsolutePinEval($board);

        $this->assertSame($expected, $absPinEval->result);
    }

    /**
     * @test
     */
    public function endgame_Rb2_check()
    {
        $board = (new StrToBoard('8/2rk2p1/4R1p1/1K1P4/4PR1P/8/1r5P/8 w - -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $absPinEval = new AbsolutePinEval($board);

        $this->assertSame($expected, $absPinEval->result);
    }

    /**
     * @test
     */
    public function giuoco_piano()
    {
        $board = (new StrToBoard('r1bqk1nr/pppp1ppp/2n5/2b1p3/2B1P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -'))
            ->create();

        $expected = [
            'w' => 1,
            'b' => 0,
        ];

        $absPinEval = new AbsolutePinEval($board);

        $this->assertSame($expected, $absPinEval->result);
    }

    /**
     * @test
     */
    public function b20_sicilian_defense()
    {
        $board = (new StrToBoard('r1b1kbnr/pp3ppp/2n1q3/4p3/1pP5/P4N2/1B1P1PPP/RN1QKB1R w KQkq -'))
            ->create();

        $expected = [
            'w' => 0,
            'b' => 0,
        ];

        $absPinEval = new AbsolutePinEval($board);

        $this->assertSame($expected, $absPinEval->result);
    }
}
