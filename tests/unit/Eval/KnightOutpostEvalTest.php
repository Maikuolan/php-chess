<?php

namespace Chess\Tests\Unit\Eval;

use Chess\Board;
use Chess\Eval\KnightOutpostEval;
use Chess\FEN\StrToBoard;
use Chess\Tests\AbstractUnitTestCase;

class KnightOutpostEvalTest extends AbstractUnitTestCase
{
    /**
     * @dataProvider wAdvancingData
     * @test
     */
    public function w_advancing($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $knightOutpostEval = (new KnightOutpostEval($board))->eval();

        $this->assertSame($expected, $knightOutpostEval);
    }

    /**
     * @dataProvider wAdvancingUnderAttackData
     * @test
     */
    public function w_advancing_under_attack($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $knightOutpostEval = (new KnightOutpostEval($board))->eval();

        $this->assertSame($expected, $knightOutpostEval);
    }

    /**
     * @dataProvider wAdvancingCanBeAttackedData
     * @test
     */
    public function w_advancing_can_be_attacked($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $knightOutpostEval = (new KnightOutpostEval($board))->eval();

        $this->assertSame($expected, $knightOutpostEval);
    }

    /**
     * @dataProvider bAdvancingData
     * @test
     */
    public function b_advancing($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $knightOutpostEval = (new KnightOutpostEval($board))->eval();

        $this->assertSame($expected, $knightOutpostEval);
    }

    /**
     * @dataProvider bAdvancingUnderAttackData
     * @test
     */
    public function b_advancing_under_attack($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $knightOutpostEval = (new KnightOutpostEval($board))->eval();

        $this->assertSame($expected, $knightOutpostEval);
    }

    /**
     * @dataProvider bAdvancingCanBeAttackedData
     * @test
     */
    public function b_advancing_can_be_attacked($expected, $fen)
    {
        $board = (new StrToBoard($fen))->create();

        $knightOutpostEval = (new KnightOutpostEval($board))->eval();

        $this->assertSame($expected, $knightOutpostEval);
    }

    public function wAdvancingData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/8/8/8/1N6/P7/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/7K/8/8/1N6/P7/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/7K/8/1N6/P7/8/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/7K/1N6/P7/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/1N5K/P7/8/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '1N3k2/P6K/8/8/8/8/8/8 w - -',
            ],
        ];
    }

    public function wAdvancingUnderAttackData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/8/8/2p5/1N6/P7/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/8/2p5/1N6/P7/8/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/2p5/1N6/P7/8/8/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/2p4K/1N6/P7/8/8/8/8 w - -',
            ],
        ];
    }

    public function wAdvancingCanBeAttackedData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/8/2p5/8/1N6/P7/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '5k2/7K/8/2p5/1N6/P7/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/7K/8/1Np5/P7/8/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/7K/1N6/P1p5/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => 1,
                    'b' => 0,
                ],
                '5k2/1N5K/P7/2p5/8/8/8/8 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '1N3k2/P6K/8/2p5/8/8/8/8 w - -',
            ],
        ];
    }

    public function bAdvancingData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/7p/6n1/8/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 1,
                ],
                '8/8/7p/6n1/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 1,
                ],
                '8/8/8/7p/6n1/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 1,
                ],
                '8/8/8/8/7p/6n1/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 1,
                ],
                '8/8/8/8/8/7p/K5n1/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/8/8/8/8/K6p/2k3n1 w - -',
            ],
        ];
    }

    public function bAdvancingUnderAttackData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/7p/6n1/5P2/8/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/7p/6n1/5P2/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/8/7p/6n1/5P2/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/8/8/7p/6n1/K4P2/2k5 w - -',
            ],
        ];
    }

    public function bAdvancingCanBeAttackedData()
    {
        return [
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/7p/6n1/8/5P2/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/7p/6n1/5P2/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 1,
                ],
                '8/8/8/7p/5Pn1/8/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 1,
                ],
                '8/8/8/8/5P1p/6n1/K7/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 1,
                ],
                '8/8/8/8/5P2/7p/K5n1/2k5 w - -',
            ],
            [
                [
                    'w' => 0,
                    'b' => 0,
                ],
                '8/8/8/8/5P2/8/K6p/2k3n1 w - -',
            ],
        ];
    }
}