<?php

namespace Chess\Tests\Unit\Variant\Chess960;

use Chess\PGN\AN\Castle;
use Chess\PGN\AN\Color;
use Chess\PGN\AN\Piece;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Chess960\Board;
use Chess\Variant\Chess960\StartPosition;

class BoardTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function get_pieces()
    {
        $startPosition = (new StartPosition())->create();
        $board = new Board($startPosition);
        $pieces = $board->getPieces();

        $this->assertSame(32, count($pieces));
    }

    /**
     * @test
     */
    public function get_castling_rule_R_B_B_K_R_Q_N_N()
    {
        $startPosition = ['R', 'B', 'B', 'K', 'R', 'Q', 'N', 'N'];

        $castlingRule = (new Board($startPosition))->getCastlingRule();

        $expected = [
            Color::W => [
                Piece::K => [
                    Castle::SHORT => [
                        'sqs' => [ 'f1', 'g1' ],
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'g1',
                        ],
                    ],
                    Castle::LONG => [
                        'sqs' => [ 'b1', 'c1' ],
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'c1',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'e1',
                            'next' => 'f1',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'a1',
                            'next' => 'd1',
                        ],
                    ],
                ],
            ],
            Color::B => [
                Piece::K => [
                    Castle::SHORT => [
                        'sqs' => [ 'f8', 'g8' ],
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'g8',
                        ],
                    ],
                    Castle::LONG => [
                        'sqs' => [ 'b8', 'c8' ],
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'c8',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'e8',
                            'next' => 'f8',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'a8',
                            'next' => 'd8',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $castlingRule);
    }

    /**
     * @test
     */
    public function get_castling_rule_Q_R_B_K_R_B_N_N()
    {
        $startPosition = ['Q', 'R', 'B', 'K', 'R', 'B', 'N', 'N'];

        $castlingRule = (new Board($startPosition))->getCastlingRule();

        $expected = [
            Color::W => [
                Piece::K => [
                    Castle::SHORT => [
                        'sqs' => [ 'f1', 'g1' ],
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'g1',
                        ],
                    ],
                    Castle::LONG => [
                        'sqs' => [ 'c1' ],
                        'sq' => [
                            'current' => 'd1',
                            'next' => 'c1',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'e1',
                            'next' => 'f1',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'b1',
                            'next' => 'd1',
                        ],
                    ],
                ],
            ],
            Color::B => [
                Piece::K => [
                    Castle::SHORT => [
                        'sqs' => [ 'f8', 'g8' ],
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'g8',
                        ],
                    ],
                    Castle::LONG => [
                        'sqs' => [ 'c8' ],
                        'sq' => [
                            'current' => 'd8',
                            'next' => 'c8',
                        ],
                    ],
                ],
                Piece::R => [
                    Castle::SHORT => [
                        'sq' => [
                            'current' => 'e8',
                            'next' => 'f8',
                        ],
                    ],
                    Castle::LONG => [
                        'sq' => [
                            'current' => 'b8',
                            'next' => 'd8',
                        ],
                    ],
                ],
            ],
        ];

        $this->assertEquals($expected, $castlingRule);
    }

    /**
     * @test
     */
    public function to_array_e4_e5()
    {
        $startPosition = ['Q', 'R', 'B', 'K', 'R', 'B', 'N', 'N'];

        $board = new Board($startPosition);

        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $expected = [
            7 => [ ' q ', ' r ', ' b ', ' k ', ' r ', ' b ', ' n ', ' n ' ],
            6 => [ ' p ', ' p ', ' p ', ' p ', ' . ', ' p ', ' p ', ' p ' ],
            5 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            4 => [ ' . ', ' . ', ' . ', ' . ', ' p ', ' . ', ' . ', ' . ' ],
            3 => [ ' . ', ' . ', ' . ', ' . ', ' P ', ' . ', ' . ', ' . ' ],
            2 => [ ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ', ' . ' ],
            1 => [ ' P ', ' P ', ' P ', ' P ', ' . ', ' P ', ' P ', ' P ' ],
            0 => [ ' Q ', ' R ', ' B ', ' K ', ' R ', ' B ', ' N ', ' N ' ],
        ];

        $this->assertSame($expected, $board->toAsciiArray());
    }
}