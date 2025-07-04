<?php

namespace Chess\Tests\Unit\Variant\Classical;

use Chess\FenToBoardFactory;
use Chess\Computer\RandomMove;
use Chess\Movetext\SanMovetext;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\RType;
use Chess\Variant\Classical\FEN\StrToBoard;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\PGN\Move;
use Chess\Variant\Classical\Board;
use Chess\Variant\Classical\Piece\B;
use Chess\Variant\Classical\Piece\K;
use Chess\Variant\Classical\Piece\N;
use Chess\Variant\Classical\Piece\P;
use Chess\Variant\Classical\Piece\Q;
use Chess\Variant\Classical\Piece\R;
use Chess\Variant\Classical\Rule\CastlingRule;

class BoardTest extends AbstractUnitTestCase
{
    static private Square $square;

    public static function setUpBeforeClass(): void
    {
        self::$square = new Square();
    }

    /**
     * @test
     */
    public function play_games()
    {
        $move = new Move();
        foreach (new \DirectoryIterator(self::DATA_FOLDER."/pgn/") as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            $filename = $fileInfo->getFilename();
            $movetext = file_get_contents(self::DATA_FOLDER."/pgn/$filename");
            $san = new SanMovetext($move, $movetext);
            if ($san->validate()) {
                $board = (new StrToBoard('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -'))
                    ->create();
                foreach ($san->moves as $key => $val) {
                    $this->assertTrue($board->play($board->turn, $val));
                }
            }
        }
    }

    /**
     * @test
     */
    public function castling_ability_in_C67()
    {
        $C67 = file_get_contents(self::DATA_FOLDER.'/sample/C67.pgn');

        $board = (new SanPlay($C67))->validate()->board;

        $expected = 'kq';

        $this->assertSame($expected, $board->castlingAbility);
    }

    /**
     * @test
     */
    public function history_in_D06()
    {
        $D06 = file_get_contents(self::DATA_FOLDER.'/sample/D06.pgn');
        $D06 = str_replace("\n", "", $D06);

        $board = (new SanPlay($D06))->validate()->board;

        $expected = [
            [
                'pgn' => 'd4',
                'case' => Move::PAWN,
                'color' => Color::W,
                'id' => Piece::P,
                'from' => 'd2',
                'to' => 'd4',
                'fen' => 'rnbqkbnr/pppppppp/8/8/3P4/8/PPP1PPPP/RNBQKBNR b KQkq d3',
            ],
            [
                'pgn' => 'd5',
                'case' => Move::PAWN,
                'color' => Color::B,
                'id' => Piece::P,
                'from' => 'd7',
                'to' => 'd5',
                'fen' => 'rnbqkbnr/ppp1pppp/8/3p4/3P4/8/PPP1PPPP/RNBQKBNR w KQkq d6',
            ],
            [
                'pgn' => 'c4',
                'case' => Move::PAWN,
                'color' => Color::W,
                'id' => Piece::P,
                'from' => 'c2',
                'to' => 'c4',
                'fen' => 'rnbqkbnr/ppp1pppp/8/3p4/2PP4/8/PP2PPPP/RNBQKBNR b KQkq c3',
            ],
            [
                'pgn' => 'c5',
                'case' => Move::PAWN,
                'color' => Color::B,
                'id' => Piece::P,
                'from' => 'c7',
                'to' => 'c5',
                'fen' => 'rnbqkbnr/pp2pppp/8/2pp4/2PP4/8/PP2PPPP/RNBQKBNR w KQkq c6',
            ],
        ];

        $this->assertEquals($expected, $board->history);
    }

    /**
     * @test
     */
    public function history_in_C60()
    {
        $C60 = file_get_contents(self::DATA_FOLDER.'/sample/C60.pgn');
        $C60 = str_replace("\n", "", $C60);

        $board = (new SanPlay($C60))->validate()->board;

        $expected = [
            [
                'pgn' => 'e4',
                'case' => Move::PAWN,
                'color' => Color::W,
                'id' => Piece::P,
                'from' => 'e2',
                'to' => 'e4',
                'fen' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            ],
            [
                'pgn' => 'e5',
                'case' => Move::PAWN,
                'color' => Color::B,
                'id' => Piece::P,
                'from' => 'e7',
                'to' => 'e5',
                'fen' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/8/PPPP1PPP/RNBQKBNR w KQkq e6',
            ],
            [
                'pgn' => 'Nf3',
                'case' => Move::KNIGHT,
                'color' => Color::W,
                'id' => Piece::N,
                'from' => 'g1',
                'to' => 'f3',
                'fen' => 'rnbqkbnr/pppp1ppp/8/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R b KQkq -',
            ],
            [
                'pgn' => 'Nc6',
                'case' => Move::KNIGHT,
                'color' => Color::B,
                'id' => Piece::N,
                'from' => 'b8',
                'to' => 'c6',
                'fen' => 'r1bqkbnr/pppp1ppp/2n5/4p3/4P3/5N2/PPPP1PPP/RNBQKB1R w KQkq -',
            ],
            [
                'pgn' => 'Bb5',
                'case' => Move::PIECE,
                'color' => Color::W,
                'id' => Piece::B,
                'from' => 'f1',
                'to' => 'b5',
                'fen' => 'r1bqkbnr/pppp1ppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R b KQkq -',
            ],
            [
                'pgn' => 'Be7',
                'case' => Move::PIECE,
                'color' => Color::B,
                'id' => Piece::B,
                'from' => 'f8',
                'to' => 'e7',
                'fen' => 'r1bqk1nr/ppppbppp/2n5/1B2p3/4P3/5N2/PPPP1PPP/RNBQK2R w KQkq -',
            ],
        ];

        $this->assertEquals($expected, $board->history);
    }

    /**
     * @test
     */
    public function history_in_C11()
    {
        $C11 = file_get_contents(self::DATA_FOLDER.'/sample/C11.pgn');
        $C11 = str_replace("\n", "", $C11);

        $board = (new SanPlay($C11))->validate()->board;

        $expected = [
            [
                'pgn' => 'e4',
                'case' => Move::PAWN,
                'color' => Color::W,
                'id' => Piece::P,
                'from' => 'e2',
                'to' => 'e4',
                'fen' => 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3',
            ],
            [
                'pgn' => 'e6',
                'case' => Move::PAWN,
                'color' => Color::B,
                'id' => Piece::P,
                'from' => 'e7',
                'to' => 'e6',
                'fen' => 'rnbqkbnr/pppp1ppp/4p3/8/4P3/8/PPPP1PPP/RNBQKBNR w KQkq -',
            ],
            [
                'pgn' => 'd4',
                'case' => Move::PAWN,
                'color' => Color::W,
                'id' => Piece::P,
                'from' => 'd2',
                'to' => 'd4',
                'fen' => 'rnbqkbnr/pppp1ppp/4p3/8/3PP3/8/PPP2PPP/RNBQKBNR b KQkq d3',
            ],
            [
                'pgn' => 'd5',
                'case' => Move::PAWN,
                'color' => Color::B,
                'id' => Piece::P,
                'from' => 'd7',
                'to' => 'd5',
                'fen' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/8/PPP2PPP/RNBQKBNR w KQkq d6',
            ],
            [
                'pgn' => 'Nc3',
                'case' => Move::KNIGHT,
                'color' => Color::W,
                'id' => Piece::N,
                'from' => 'b1',
                'to' => 'c3',
                'fen' => 'rnbqkbnr/ppp2ppp/4p3/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR b KQkq -',
            ],
            [
                'pgn' => 'Nf6',
                'case' => Move::KNIGHT,
                'color' => Color::B,
                'id' => Piece::N,
                'from' => 'g8',
                'to' => 'f6',
                'fen' => 'rnbqkb1r/ppp2ppp/4pn2/3p4/3PP3/2N5/PPP2PPP/R1BQKBNR w KQkq -',
            ],
        ];

        $this->assertEquals($expected, $board->history);
    }

    /**
     * @test
     */
    public function history_in_a4_h5_Ra3_Rh6()
    {
        $board = new Board();

        $board->play('w', 'a4');
        $board->play('b', 'h5');
        $board->play('w', 'Ra3');
        $board->play('b', 'Rh6');

        $expected = [
            [
                'pgn' => 'a4',
                'case' => Move::PAWN,
                'color' => Color::W,
                'id' => Piece::P,
                'from' => 'a2',
                'to' => 'a4',
                'fen' => 'rnbqkbnr/pppppppp/8/8/P7/8/1PPPPPPP/RNBQKBNR b KQkq a3',
            ],
            [
                'pgn' => 'h5',
                'case' => Move::PAWN,
                'color' => Color::B,
                'id' => Piece::P,
                'from' => 'h7',
                'to' => 'h5',
                'fen' => 'rnbqkbnr/ppppppp1/8/7p/P7/8/1PPPPPPP/RNBQKBNR w KQkq h6',
            ],
            [
                'pgn' => 'Ra3',
                'case' => Move::PIECE,
                'color' => Color::W,
                'id' => Piece::R,
                'from' => 'a1',
                'to' => 'a3',
                'fen' => 'rnbqkbnr/ppppppp1/8/7p/P7/R7/1PPPPPPP/1NBQKBNR b Kkq -',
            ],
            [
                'pgn' => 'Rh6',
                'case' => Move::PIECE,
                'color' => Color::B,
                'id' => Piece::R,
                'from' => 'h8',
                'to' => 'h6',
                'fen' => 'rnbqkbn1/ppppppp1/7r/7p/P7/R7/1PPPPPPP/1NBQKBNR w Kq -',
            ],
        ];

        $this->assertEquals($expected, $board->history);
    }

    /**
     * @test
     */
    public function pieces_in_A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->board;

        $this->assertSame(14, count($board->pieces(Color::W)));
        $this->assertSame(13, count($board->pieces(Color::B)));
    }

    /**
     * @test
     */
    public function pieces_in_A74()
    {
        $A74 = file_get_contents(self::DATA_FOLDER.'/sample/A74.pgn');

        $board = (new SanPlay($A74))->validate()->board;

        $this->assertSame(15, count($board->pieces(Color::W)));
        $this->assertSame(15, count($board->pieces(Color::B)));
    }

    /**
     * @test
     */
    public function pieces_in_B56()
    {
        $B56 = file_get_contents(self::DATA_FOLDER.'/sample/B56.pgn');

        $board = (new SanPlay($B56))->validate()->board;

        $this->assertSame(15, count($board->pieces(Color::W)));
        $this->assertSame(15, count($board->pieces(Color::B)));
    }

    /**
     * @test
     */
    public function play_w_9()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 9);
    }

    /**
     * @test
     */
    public function play_w_foo()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'foo');
    }

    /**
     * @test
     */
    public function play_w_e9()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'e9');
    }

    /**
     * @test
     */
    public function play_w_Nw3()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        (new Board())->play('w', 'Nw3');
    }

    /**
     * @test
     */
    public function init_board_and_pick_a_nonexistent_piece()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'g3', self::$square),
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'f4'));
    }

    /**
     * @test
     */
    public function play_b_Qg5()
    {
        $board = new Board();

        $this->assertFalse($board->play('b', 'Qg5'));
    }

    /**
     * @test
     */
    public function play_w_Ra6()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'Ra6'));
    }

    /**
     * @test
     */
    public function play_b_Rxa6()
    {
        $board = new Board();

        $this->assertFalse($board->play('b', 'Rxa6'));
    }

    /**
     * @test
     */
    public function play_w_CASTLE_SHORT()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function play_a_falsy_game()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('w', 'O-O-O'));
        $this->assertFalse($board->play('w', 'e5'));

        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e5'));

        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nc6'));

        $this->assertFalse($board->play('w', 'Ra2'));
        $this->assertFalse($board->play('w', 'Ra3'));
        $this->assertFalse($board->play('w', 'Ra4'));
        $this->assertFalse($board->play('w', 'Ra5'));
        $this->assertFalse($board->play('w', 'Ra6'));
        $this->assertFalse($board->play('w', 'Ra7'));
        $this->assertFalse($board->play('w', 'Ra8'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kf4()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'g3', self::$square),
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Kf4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kf4_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Kf4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kf2_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Kf2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Re7_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Re7'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_a4_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'a4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxf2()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'g3', self::$square),
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f2', self::$square, RType::CASTLE_SHORT), // rook defended by knight
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'Kxf2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'c5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_LONG()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'c5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Bb5');
        $board->play('b', 'Nf6');

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_with_threats_on_f1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd4', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f2', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new B('b', 'a6', self::$square), // bishop threatening f1
            new K('b', 'e8', self::$square),
            new B('b', 'f8', self::$square),
            new N('b', 'g8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_with_threats_on_f1_g1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd5', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f3', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new B('b', 'a6', self::$square), // bishop threatening f1
            new K('b', 'e8', self::$square),
            new B('b', 'c5', self::$square), // bishop threatening g1
            new N('b', 'g8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_with_threats_on_g1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd5', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f3', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$square),
            new B('b', 'c5', self::$square), // bishop threatening g1
            new N('b', 'g8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_LONG_with_threats_on_c1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd5', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f3', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$square),
            new B('b', 'f4', self::$square), // bishop threatening c1
            new N('b', 'g8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_castle_with_threats_on_d1_f1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd5', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f3', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$square),
            new B('b', 'f8', self::$square),
            new N('b', 'e3', self::$square), // knight threatening d1 and f1
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_castle_with_threats_on_b1_f1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd5', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f3', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$square),
            new B('b', 'f8', self::$square),
            new N('b', 'd2', self::$square), // knight threatening b1 and f1
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O'));
        $this->assertTrue($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_LONG_with_threats_on_b1_d1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd5', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f3', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$square),
            new B('b', 'f8', self::$square),
            new N('b', 'c3', self::$square), // knight threatening b1 and d1
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertFalse($board->play('w', 'O-O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_after_Kf1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd5', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f3', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$square),
            new B('b', 'f8', self::$square),
            new N('b', 'g8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kf1'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Ke1'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_CASTLE_SHORT_after_Rg1()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'd5', self::$square),
            new P('w', 'e4', self::$square),
            new P('w', 'f3', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new K('b', 'e8', self::$square),
            new B('b', 'f8', self::$square),
            new N('b', 'g8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Rg1'));
        $this->assertTrue($board->play('b', 'Nf6'));
        $this->assertTrue($board->play('w', 'Rh1'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertFalse($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_CASTLE_SHORT_with_threats()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'c2', self::$square),
            new P('w', 'c3', self::$square),
            new P('w', 'd4', self::$square),
            new P('w', 'f2', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new N('w', 'g1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new B('w', 'a3', self::$square),
            new B('w', 'd3', self::$square),
            new P('b', 'a7', self::$square),
            new P('b', 'b6', self::$square),
            new P('b', 'c7', self::$square),
            new P('b', 'e6', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h6', self::$square),
            new R('b', 'a8', self::$square, RType::CASTLE_LONG),
            new B('b', 'c8', self::$square),
            new Q('b', 'd8', self::$square),
            new K('b', 'e8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new N('b', 'd7', self::$square),
            new N('b', 'f6', self::$square)
        ];

        $castlingAbility = 'KQk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertFalse($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ra6()
    {
        $pieces = [
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new Q('w', 'd1', self::$square),
            new K('w', 'e1', self::$square),
            new B('w', 'f1', self::$square),
            new N('w', 'g1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('w', 'b2', self::$square),
            new P('w', 'c2', self::$square),
            new P('w', 'd2', self::$square),
            new P('w', 'e2', self::$square),
            new P('w', 'f2', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('b', 'a8', self::$square, RType::CASTLE_LONG),
            new N('b', 'b8', self::$square),
            new B('b', 'c8', self::$square),
            new Q('b', 'd8', self::$square),
            new K('b', 'e8', self::$square),
            new B('b', 'f8', self::$square),
            new N('b', 'g8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'a7', self::$square),
            new P('b', 'b7', self::$square),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQkq';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Ra6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Rxa6()
    {
        $pieces = [
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new K('b', 'e8', self::$square),
            new B('b', 'a6', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'Q';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Rxa6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_h6()
    {
        $pieces = [
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new K('b', 'e8', self::$square),
            new B('b', 'a6', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'Q';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertTrue($board->play('b', 'h6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_hxg6()
    {
        $pieces = [
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e1', self::$square),
            new P('w', 'g6', self::$square),
            new K('b', 'e8', self::$square),
            new B('b', 'a6', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'Q';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertTrue($board->play('b', 'hxg6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Nxc3()
    {
        $pieces = [
            new N('w', 'b1', self::$square),
            new K('w', 'e1', self::$square),
            new P('w', 'g6', self::$square),
            new K('b', 'e8', self::$square),
            new B('b', 'a6', self::$square),
            new P('b', 'c3', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Nxc3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_CASTLE_SHORT()
    {
        $pieces = [
            new R('w', 'a1', self::$square, RType::CASTLE_LONG),
            new N('w', 'b1', self::$square),
            new B('w', 'c1', self::$square),
            new Q('w', 'd1', self::$square),
            new K('w', 'e1', self::$square),
            new B('w', 'f1', self::$square),
            new N('w', 'g1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('w', 'a2', self::$square),
            new P('w', 'b2', self::$square),
            new P('w', 'c2', self::$square),
            new P('w', 'd2', self::$square),
            new P('w', 'e2', self::$square),
            new P('w', 'f2', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('b', 'a8', self::$square, RType::CASTLE_LONG),
            new N('b', 'b8', self::$square),
            new B('b', 'c8', self::$square),
            new Q('b', 'd8', self::$square),
            new K('b', 'e8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT),
            new P('b', 'a7', self::$square),
            new P('b', 'b7', self::$square),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = 'KQkq';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertTrue($board->play('b', 'O-O'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke4_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Ke4'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg3_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kg3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg2_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kg2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke2_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Ke2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Ke3_in_check()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'f3', self::$square), // in check!
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Ke3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kg2()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'g3', self::$square),
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f5', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kg2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxh2()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'g3', self::$square),
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'h2', self::$square, RType::CASTLE_SHORT),
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kxh2'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_Kxf3()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'a3', self::$square),
            new P('w', 'c3', self::$square),
            new R('w', 'e6', self::$square, RType::CASTLE_LONG),
            new K('w', 'g3', self::$square),
            new P('b', 'a6', self::$square),
            new P('b', 'b5', self::$square),
            new P('b', 'c4', self::$square),
            new N('b', 'd3', self::$square),
            new R('b', 'f3', self::$square, RType::CASTLE_SHORT), // rook not defended
            new K('b', 'g5', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'Kxf3'));
    }

    /**
     * @test
     */
    public function init_board_and_play_b_exf3()
    {
        $pieces = [
            new P('w', 'e2', self::$square),
            new P('w', 'f2', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'e4', self::$square),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square),
            new K('b', 'e8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT)
        ];

        $castlingAbility = 'Kk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'f4'));
        $this->assertTrue($board->play('b', 'exf3')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_w_exf6()
    {
        $pieces = [
            new P('w', 'e5', self::$square),
            new P('w', 'f2', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'e7', self::$square),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square),
            new K('b', 'e8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT)
        ];

        $castlingAbility = 'Kk';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertTrue($board->play('b', 'f5'));
        $this->assertTrue($board->play('w', 'exf6')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_b_gxh3()
    {
        $pieces = [
            new P('w', 'e2', self::$square),
            new P('w', 'f2', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'e7', self::$square),
            new P('b', 'f7', self::$square),
            new P('b', 'g4', self::$square),
            new P('b', 'h7', self::$square),
            new K('b', 'e8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT)
        ];

        $castlingAbility = 'Kk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h4'));
        $this->assertTrue($board->play('b', 'gxh3')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_b_hxg3()
    {
        $pieces = [
            new P('w', 'e2', self::$square),
            new P('w', 'f2', self::$square),
            new P('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'e7', self::$square),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h4', self::$square),
            new K('b', 'e8', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_SHORT)
        ];

        $castlingAbility = 'Kk';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'g4'));
        $this->assertTrue($board->play('b', 'hxg3'));
    }

    /**
     * @test
     */
    public function play_a_sequence_of_moves_w_exf6()
    {
        $board = new Board();
        $this->assertTrue($board->play('w', 'e4'));
        $this->assertTrue($board->play('b', 'e6'));
        $this->assertTrue($board->play('w', 'd4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertTrue($board->play('w', 'Nc3'));
        $this->assertTrue($board->play('b', 'Bb4'));
        $this->assertTrue($board->play('w', 'e5'));
        $this->assertTrue($board->play('b', 'c5'));
        $this->assertTrue($board->play('w', 'Qg4'));
        $this->assertTrue($board->play('b', 'Ne7'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'Nbc6'));
        $this->assertTrue($board->play('w', 'a3'));
        $this->assertTrue($board->play('b', 'Bxc3+'));
        $this->assertTrue($board->play('w', 'bxc3'));
        $this->assertTrue($board->play('b', 'Qc7'));
        $this->assertTrue($board->play('w', 'Rb1'));
        $this->assertTrue($board->play('b', 'O-O'));
        $this->assertTrue($board->play('w', 'Bd3'));
        $this->assertTrue($board->play('b', 'f5'));
        $pawn_e5 = $board->pieceBySq('e5');
        $pawn_e5->moveSqs(); // this creates the en passant property
        $this->assertSame('f6', $pawn_e5->enPassantSq);
        $this->assertTrue($board->play('w', 'exf6'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_cxb6()
    {
        $pieces = [
            new P('w', 'a2', self::$square),
            new P('w', 'b2', self::$square),
            new P('w', 'c5', self::$square),
            new R('w', 'd1', self::$square, RType::CASTLE_LONG),
            new K('w', 'e4', self::$square),
            new P('b', 'a7', self::$square),
            new P('b', 'b7', self::$square),
            new P('b', 'c7', self::$square),
            new K('b', 'g6', self::$square),
            new R('b', 'h8', self::$square, RType::CASTLE_LONG),
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertTrue($board->play('b', 'b5'));
        $this->assertTrue($board->play('w', 'cxb6')); // en passant
    }

    /**
     * @test
     */
    public function init_board_and_play_w_h8_q()
    {
        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h8=Q'));
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_q()
    {
        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->playLan('w', 'h7h8q'));
    }

    /**
     * @test
     */
    public function init_board_and_play_w_h8_n()
    {
        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h8=N'));
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_n()
    {
        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $board->playLan('w', 'h7h8n');

        $this->assertEquals('N', $board->pieceBySq('h8')->id);
    }

    /**
     * @test
     */
    public function init_board_and_play_w_h8_r()
    {
        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h8=R'));
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_r()
    {
        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $board->playLan('w', 'h7h8r');

        $this->assertEquals('R', $board->pieceBySq('h8')->id);
    }

    /**
     * @test
     */
    public function init_board_and_play_w_h8_b()
    {
        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'h8=B'));
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_b()
    {
        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $board->playLan('w', 'h7h8b');

        $this->assertEquals('B', $board->pieceBySq('h8')->id);
    }

    /**
     * @test
     */
    public function init_board_and_play_lan_w_h8_z()
    {
        $this->expectException(\Chess\Exception\UnknownNotationException::class);

        $pieces = [
            new P('w', 'g2', self::$square),
            new P('w', 'h7', self::$square),
            new K('w', 'e1', self::$square),
            new R('w', 'h1', self::$square, RType::CASTLE_SHORT),
            new P('b', 'c7', self::$square),
            new P('b', 'd7', self::$square),
            new P('b', 'e7', self::$square),
            new B('b', 'd6', self::$square),
            new K('b', 'e8', self::$square)
        ];

        $castlingAbility = 'K';

        $board = new Board($pieces, $castlingAbility);

        $board->playLan('w', 'h7h8z');
    }

    /**
     * @test
     */
    public function init_board_and_checkmate_w_Qd7()
    {
        $pieces = [
            new P('w', 'd5', self::$square),
            new Q('w', 'f5', self::$square),
            new K('w', 'g2', self::$square),
            new P('w', 'h2', self::$square),
            new R('w', 'h8', self::$square, RType::CASTLE_LONG),
            new K('b', 'e7', self::$square),
            new P('b', 'f7', self::$square),
            new P('b', 'g7', self::$square),
            new P('b', 'h7', self::$square)
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);

        $this->assertTrue($board->play('w', 'd6+'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertFalse($board->play('b', 'Kd7'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertFalse($board->play('b', 'Ke6'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('b', 'Kxd6'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('w', 'Re8'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('b', 'Kc7'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('w', 'Re7+'));
        $this->assertTrue($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('b', 'Kd8'));
        $this->assertFalse($board->isCheck());
        $this->assertFalse($board->isMate());
        $this->assertTrue($board->play('w', 'Qd7#'));
        $this->assertTrue($board->isCheck());
        $this->assertTrue($board->isMate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_king_and_queen_vs_king()
    {
        $pieces = [
            new K('b', 'h1', self::$square),
            new K('w', 'a8', self::$square),
            new Q('w', 'f2', self::$square),
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_king_and_pawn_vs_king()
    {
        $pieces = [
            new K('w', 'f6', self::$square),
            new P('w', 'f7', self::$square),
            new K('b', 'f8', self::$square),
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_king_and_rook_vs_king_and_bishop()
    {
        $pieces = [
            new K('w', 'b6', self::$square),
            new R('w', 'h8', self::$square, RType::CASTLE_LONG),
            new K('b', 'a8', self::$square),
            new B('b', 'b8', self::$square),
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function init_board_stalemate_endgame()
    {
        $pieces = [
            new K('w', 'g1', self::$square),
            new Q('w', 'd1', self::$square),
            new R('w', 'a5', self::$square, RType::CASTLE_SHORT),
            new R('w', 'b7', self::$square, RType::CASTLE_LONG),
            new P('w', 'f6', self::$square),
            new P('w', 'g5', self::$square),
            new K('b', 'e6', self::$square),
        ];

        $castlingAbility = '-';

        $board = new Board($pieces, $castlingAbility);
        $board->turn = 'b';

        $this->assertFalse($board->isMate());
        $this->assertTrue($board->isStalemate());
    }

    /**
     * @test
     */
    public function to_array_e4_e5()
    {
        $board = new Board();
        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', 'p', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function to_array_A59()
    {
        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->board;

        $expected = [
            7 => [ 'r', 'n', '.', 'q', 'k', 'b', '.', 'r' ],
            6 => [ '.', '.', '.', '.', 'p', 'p', '.', 'p' ],
            5 => [ '.', '.', '.', 'p', '.', 'n', 'p', '.' ],
            4 => [ '.', '.', 'p', 'P', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', 'N', '.', '.', '.', 'P', '.' ],
            1 => [ 'P', 'P', '.', '.', '.', 'P', '.', 'P' ],
            0 => [ 'R', '.', 'B', 'Q', '.', 'K', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function to_array_A74()
    {
        $A74 = file_get_contents(self::DATA_FOLDER.'/sample/A74.pgn');

        $board = (new SanPlay($A74))->validate()->board;

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', '.', 'r', 'k', '.' ],
            6 => [ '.', 'p', '.', '.', '.', 'p', 'b', 'p' ],
            5 => [ 'p', '.', '.', 'p', '.', 'n', 'p', '.' ],
            4 => [ '.', '.', 'p', 'P', '.', '.', '.', '.' ],
            3 => [ 'P', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', 'N', '.', '.', 'N', '.', '.' ],
            1 => [ '.', 'P', '.', '.', 'B', 'P', 'P', 'P' ],
            0 => [ 'R', '.', 'B', 'Q', '.', 'R', 'K', '.' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function to_array_C11()
    {
        $C11 = file_get_contents(self::DATA_FOLDER.'/sample/C11.pgn');

        $board = (new SanPlay($C11))->validate()->board;

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', '.', 'r' ],
            6 => [ 'p', 'p', 'p', '.', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', 'p', 'n', '.', '.' ],
            4 => [ '.', '.', '.', 'p', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', 'P', 'P', '.', '.', '.' ],
            2 => [ '.', '.', 'N', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', '.', '.', 'P', 'P', 'P' ],
            0 => [ 'R', '.', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function undo_e4_e5()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');

        $board = $board->undo();

        $expected = 'rnbqkbnr/pppppppp/8/8/4P3/8/PPPP1PPP/RNBQKBNR b KQkq e3';

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function undo_e4_b6_Nf3_Bb7_Bc4_Nc6_O_O()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'b6');
        $board->play('w', 'Nf3');
        $board->play('b', 'Bb7');
        $board->play('w', 'Bc4');
        $board->play('b', 'Nc6');
        $board->play('w', 'O-O');

        $board = $board->undo();

        $expected = 'r2qkbnr/pbpppppp/1pn5/8/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -';

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function undo_e4_b6_Nf3_Bb7_Bc4_Nc6_Ke2()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'b6');
        $board->play('w', 'Nf3');
        $board->play('b', 'Bb7');
        $board->play('w', 'Bc4');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ke2');

        $board = $board->undo();

        $expected = 'r2qkbnr/pbpppppp/1pn5/8/2B1P3/5N2/PPPP1PPP/RNBQK2R w KQkq -';

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function undo_e4_e5_Nf3_Nf6_Be2_Be7_O_O()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Be2');
        $board->play('b', 'Be7');
        $board->play('w', 'O-O');

        $board = $board->undo();

        $expected = 'rnbqk2r/ppppbppp/5n2/4p3/4P3/5N2/PPPPBPPP/RNBQK2R w KQkq -';

        $this->assertSame($expected, $board->toFen());
    }

    /**
     * @test
     */
    public function undo_e4_e5_Nf3_Nf6_Be2_Be7_O_O_legal()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Be2');
        $board->play('b', 'Be7');
        $board->play('w', 'O-O');

        $board = $board->undo();

        $expected = ['f1', 'g1'];

        $this->assertEquals($expected, $board->legal('e1'));
    }

    /**
     * @test
     */
    public function undo_e4_e5_Nf3_Nc6()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');

        $board = $board->undo();
        $board = $board->undo();
        $board = $board->undo();
        $board = $board->undo();

        $board->play('w', 'e4');

        $expected = [
            7 => [ 'r', 'n', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'p', 'p', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', 'P', '.', '.', '.' ],
            2 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            1 => [ 'P', 'P', 'P', 'P', '.', 'P', 'P', 'P' ],
            0 => [ 'R', 'N', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function king_sqs_e4_e5_Nf3_Nf6_Bc4_Be7()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $king = $board->pieceBySq('e1');

        $expected = [
            'e2',
            'f1',
            'g1',
        ];

        $this->assertEqualsCanonicalizing($expected, $king->moveSqs());
    }

    /**
     * @test
     */
    public function king_sqs_e4_e5_Nf3_Nf6_Bc4_Be7_Kf1()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $this->assertTrue($board->play('w', 'Kf1'));
    }

    /**
     * @test
     */
    public function king_sqs_e4_e5_Nf3_Nf6_Bc4_Be7_Ke2()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $this->assertTrue($board->play('w', 'Ke2'));
    }

    /**
     * @test
     */
    public function king_sqs_e4_e5_Nf3_Nf6_Bc4_Be7_O_O()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $this->assertTrue($board->play('w', 'O-O'));
    }

    /**
     * @test
     */
    public function legal_e4_e5_Nf3_Nf6_Bc4_Be7()
    {
        $board = new Board();

        $board->play('w', 'e4');
        $board->play('b', 'e5');
        $board->play('w', 'Nf3');
        $board->play('b', 'Nf6');
        $board->play('w', 'Bc4');
        $board->play('b', 'Be7');

        $expected = ['e2', 'f1', 'g1'];

        $this->assertEquals($expected, $board->legal('e1'));
    }

    /**
     * @test
     */
    public function play_lan_w_foo()
    {
        $board = new Board();

        $this->assertFalse($board->playLan('w', 'foo'));
    }

    /**
     * @test
     */
    public function play_lan_w_e2e4()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
    }

    /**
     * @test
     */
    public function play_lan_C00()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertTrue($board->playLan('b', 'e7e6'));
        $this->assertTrue($board->playLan('w', 'd2d4'));
        $this->assertTrue($board->playLan('b', 'd7d5'));
        $this->assertTrue($board->playLan('w', 'c1e3'));
    }

    /**
     * @test
     */
    public function play_lan_B00()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertTrue($board->playLan('b', 'b8c6'));
        $this->assertTrue($board->playLan('w', 'g1f3'));
        $this->assertTrue($board->playLan('b', 'd7d6'));
        $this->assertTrue($board->playLan('w', 'f1e2'));
        $this->assertTrue($board->playLan('b', 'c8e6'));
        $this->assertTrue($board->playLan('w', 'e1g1'));
        $this->assertTrue($board->playLan('b', 'd8d7'));
        $this->assertTrue($board->playLan('w', 'h2h3'));
        $this->assertTrue($board->playLan('b', 'e8c8'));
    }

    /**
     * @test
     */
    public function play_lan_e2e4_d7d5_a2a3_d5e4()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertTrue($board->playLan('b', 'd7d5'));
        $this->assertTrue($board->playLan('w', 'a2a3'));
        $this->assertTrue($board->playLan('b', 'd5e4'));
    }

    /**
     * @test
     */
    public function play_lan_e2e4_e7e5()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertFalse($board->playLan('w', 'e7e5'));
    }

    /**
     * @test
     */
    public function play_lan_b1a3_d7d5()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'b1a3'));
        $this->assertTrue($board->playLan('b', 'd7d5'));
    }

    /**
     * @test
     */
    public function play_d3_d6___Nd7_Nf3()
    {
        $board = new Board();

        $this->assertTrue($board->play('w', 'd3'));
        $this->assertTrue($board->play('b', 'd6'));
        $this->assertTrue($board->play('w', 'e3'));
        $this->assertTrue($board->play('b', 'e6'));
        $this->assertTrue($board->play('w', 'Nd2'));
        $this->assertTrue($board->play('b', 'Nd7'));
        $this->assertTrue($board->play('w', 'Nd2f3'));

        $expected = [
            7 => [ 'r', '.', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'n', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', 'p', 'p', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', 'P', 'P', 'N', '.', '.' ],
            1 => [ 'P', 'P', 'P', '.', '.', 'P', 'P', 'P' ],
            0 => [ 'R', '.', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_lan_d2d3_d7d6___b8d7_d2f3()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'd2d3'));
        $this->assertTrue($board->playLan('b', 'd7d6'));
        $this->assertTrue($board->playLan('w', 'e2e3'));
        $this->assertTrue($board->playLan('b', 'e7e6'));
        $this->assertTrue($board->playLan('w', 'b1d2'));
        $this->assertTrue($board->playLan('b', 'b8d7'));
        $this->assertTrue($board->playLan('w', 'd2f3'));

        $expected = [
            7 => [ 'r', '.', 'b', 'q', 'k', 'b', 'n', 'r' ],
            6 => [ 'p', 'p', 'p', 'n', '.', 'p', 'p', 'p' ],
            5 => [ '.', '.', '.', 'p', 'p', '.', '.', '.' ],
            4 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            3 => [ '.', '.', '.', '.', '.', '.', '.', '.' ],
            2 => [ '.', '.', '.', 'P', 'P', 'N', '.', '.' ],
            1 => [ 'P', 'P', 'P', '.', '.', 'P', 'P', 'P' ],
            0 => [ 'R', '.', 'B', 'Q', 'K', 'B', 'N', 'R' ],
        ];

        $this->assertSame($expected, $board->toArray());
    }

    /**
     * @test
     */
    public function play_d4_d5___axb5_cxb5()
    {
        $board = new Board();

        $this->assertTrue($board->play('w', 'd4'));
        $this->assertTrue($board->play('b', 'd5'));
        $this->assertTrue($board->play('w', 'c4'));
        $this->assertTrue($board->play('b', 'dxc4'));
        $this->assertTrue($board->play('w', 'Nf3'));
        $this->assertTrue($board->play('b', 'b5'));
        $this->assertTrue($board->play('w', 'a4'));
        $this->assertTrue($board->play('b', 'c6'));
        $this->assertTrue($board->play('w', 'axb5'));
        $this->assertTrue($board->play('b', 'cxb5'));
    }

    /**
     * @test
     */
    public function play_lan_d2d4_d7d5___a4b5_c6b5()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'd2d4'));
        $this->assertTrue($board->playLan('b', 'd7d5'));
        $this->assertTrue($board->playLan('w', 'c2c4'));
        $this->assertTrue($board->playLan('b', 'd5c4'));
        $this->assertTrue($board->playLan('w', 'g1f3'));
        $this->assertTrue($board->playLan('b', 'b7b5'));
        $this->assertTrue($board->playLan('w', 'a2a4'));
        $this->assertTrue($board->playLan('b', 'c7c6'));
        $this->assertTrue($board->playLan('w', 'a4b5'));
        $this->assertTrue($board->playLan('b', 'c6b5'));
    }

    /**
     * @test
     */
    public function is_fivefold_repetition()
    {
        $board = new Board();

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ng1');
        $board->play('b', 'Nb8');

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ng1');
        $board->play('b', 'Nb8');

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ng1');
        $board->play('b', 'Nb8');

        $board->play('w', 'Nf3');
        $board->play('b', 'Nc6');
        $board->play('w', 'Ng1');
        $board->play('b', 'Nb8');

        $board->play('w', 'Nf3');

        $this->assertTrue($board->isFivefoldRepetition());
    }

    /**
     * @test
     */
    public function play_lan_e2e4_f7f5_d1h5()
    {
        $board = new Board();

        $this->assertTrue($board->playLan('w', 'e2e4'));
        $this->assertTrue($board->playLan('b', 'f7f5'));
        $this->assertTrue($board->playLan('w', 'd1h5'));

        $expected = '1.e4 f5 2.Qh5+';

        $this->assertSame($expected, $board->movetext());
    }

    /**
     * @test
     */
    public function play_exe4_exe5()
    {
        $board = new Board();

        $this->assertFalse($board->play('w', 'exe4'));
        $this->assertFalse($board->play('b', 'exe5'));
    }

    /**
     * @test
     */
    public function play_h4_a5_h5_g5()
    {
        $board = new Board();

        $board->play('w', 'h4');
        $board->play('b', 'a5');
        $board->play('w', 'h5');
        $board->play('b', 'g5');

        $expected = ['h6', 'g6'];

        $this->assertEquals($expected, $board->legal('h5'));
    }

    /**
     * @test
     */
    public function play_lan_h2h4_a7a5_h4h5_g7g5()
    {
        $board = new Board();

        $board->playLan('w', 'h2h4');
        $board->playLan('b', 'a7a5');
        $board->playLan('w', 'h4h5');
        $board->playLan('b', 'g7g5');

        $expected = ['h6', 'g6'];

        $this->assertEquals($expected, $board->legal('h5'));
    }

    /**
     * @test
     */
    public function legal_a1()
    {
        $board = new Board();

        $expected = [];

        $this->assertEquals($expected, $board->legal('a1'));
    }

    /**
     * @test
     */
    public function legal_e2()
    {
        $board = new Board();

        $expected = ['e3', 'e4'];

        $this->assertEquals($expected, $board->legal('e2'));
    }

    /**
     * @test
     */
    public function legal_e7()
    {
        $board = new Board();

        $board->playLan('w', 'e2e4');

        $expected = ['e6', 'e5'];

        $this->assertEquals($expected, $board->legal('e7'));
    }

    /**
     * @test
     */
    public function legal_e1()
    {
        $board = (new SanPlay('1.f4 e5 2.e4 a6 3.Bc4 a5 4.Nh3 Qh4+'))->validate()->board;

        $expected = ['e2', 'f1'];

        $this->assertEquals($expected, $board->legal('e1'));
    }

    /**
     * @test
     */
    public function is_fifty_move_draw_C68()
    {
        $C68 = file_get_contents(self::DATA_FOLDER.'/sample/C68.pgn');

        $board = (new SanPlay($C68))->validate()->board;

        $this->assertFalse($board->isFiftyMoveDraw());
    }

    /**
     * @test
     */
    public function is_fifty_move_draw_endgame_49_moves()
    {
        $board = FenToBoardFactory::create('7k/8/8/8/8/8/8/K7 w - - 0 1');

        for ($i = 0; $i < 99; $i++) {
            if ($move = (new RandomMove($board))->move()) {
                $board->play($board->turn, $move['pgn']);
            }
        }

        $this->assertFalse($board->isFiftyMoveDraw());
    }

    /**
     * @test
     */
    public function is_fifty_move_draw_endgame_50_moves()
    {
        $board = FenToBoardFactory::create('7k/8/8/8/8/8/8/K7 w - - 0 1');

        for ($i = 0; $i < 100; $i++) {
            if ($move = (new RandomMove($board))->move()) {
                $board->play($board->turn, $move['pgn']);
            }
        }

        $this->assertTrue($board->isFiftyMoveDraw());
    }

    /**
     * @test
     */
    public function is_dead_position_draw_K_vs_k()
    {
        $board = FenToBoardFactory::create('8/5k2/8/8/8/2K5/8/8 w - - 0 1');

        $this->assertTrue($board->isDeadPositionDraw());
    }

    /**
     * @test
     */
    public function is_dead_position_draw_K_B_vs_k()
    {
        $board = FenToBoardFactory::create('8/5k2/8/8/8/2KB4/8/8 w - - 0 1');

        $this->assertTrue($board->isDeadPositionDraw());
    }

    /**
     * @test
     */
    public function is_dead_position_draw_K_B_vs_k_b()
    {
        $board = FenToBoardFactory::create('k2B4/b1K5/8/8/8/8/8/8 w - - 0 1');

        $this->assertTrue($board->isDeadPositionDraw());
    }

    /**
     * @test
     */
    public function is_dead_position_draw_K_B_B_vs_k()
    {
        $board = FenToBoardFactory::create('8/5k2/8/8/8/2KB4/4B3/8 w - - 0 1');

        $this->assertTrue($board->isDeadPositionDraw());
    }

    /**
     * @test
     */
    public function is_not_dead_position_draw_K_B_vs_k_b()
    {
        $board = FenToBoardFactory::create('k7/b1K5/8/8/8/8/4B3/8 w - - 0 1');

        $this->assertFalse($board->isDeadPositionDraw());
    }

    /**
     * @test
     */
    public function is_not_dead_position_draw_K_P_vs_k()
    {
        $board = FenToBoardFactory::create('8/5k2/8/8/8/2KP4/8/8 w - - 0 1');

        $this->assertFalse($board->isDeadPositionDraw());
    }

    /**
     * @test
     */
    public function is_not_dead_position_draw_K_B_B_vs_k()
    {
        $board = FenToBoardFactory::create('8/5k2/8/8/8/2KBB3/8/8 w - - 0 1');

        $this->assertFalse($board->isDeadPositionDraw());
    }

    /**
     * @test
     */
    public function c6_is_pinned()
    {
        $board = FenToBoardFactory::create('r1bqkbnr/ppp2ppp/2np4/1B2p3/4P3/5N2/PPPP1PPP/RNBQ1RK1 b kq -');

        $expected = [];

        $this->assertEquals($expected, $board->legal('c6'));
    }

    /**
     * @test
     */
    public function c3_defends_b4_and_d4()
    {
        $board = FenToBoardFactory::create('8/5k1r/5pp1/8/1N1B4/1KP5/8/8 w - -');

        $expected = ["b4", "d4"];

        $this->assertEquals($expected, $board->pieceBySq("c3")->defendedSqs());
    }

    /**
     * @test
     */
    public function count_sq_start()
    {
        $board = new Board();

        $sqCount = $board->sqCount();

        $expected = [
            'a3', 'a4', 'a5', 'a6',
            'b3', 'b4', 'b5', 'b6',
            'c3', 'c4', 'c5', 'c6',
            'd3', 'd4', 'd5', 'd6',
            'e3', 'e4', 'e5', 'e6',
            'f3', 'f4', 'f5', 'f6',
            'g3', 'g4', 'g5', 'g6',
            'h3', 'h4', 'h5', 'h6',
        ];

        $this->assertEqualsCanonicalizing($expected, $sqCount['free']);
    }
}
