<?php

namespace Chess\Variant\Capablanca;

use Chess\Piece\A;
use Chess\Piece\C;
use Chess\Piece\B;
use Chess\Piece\K;
use Chess\Piece\N;
use Chess\Piece\P;
use Chess\Piece\Q;
use Chess\Piece\R;
use Chess\Piece\RType;
use Chess\Variant\Capablanca\Rule\CastlingRule;
use Chess\Variant\Capablanca\PGN\Move;
use Chess\Variant\Capablanca\PGN\AN\Square;
use Chess\Variant\Classical\FEN\Field\CastlingAbility;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\Board as ClassicalBoard;

/**
 * Board
 *
 * Chess board representation that allows to play a game of Capablanca chess in
 * Portable Game Notation (PGN) format.
 *
 * @author Jordi Bassagañas
 * @license GPL
 */
final class Board extends ClassicalBoard
{
    /**
     * Constructor.
     *
     * @param array $startPos
     */
    public function __construct()
    {
        $this->size = Square::SIZE;
        $this->castlingAbility = CastlingAbility::START;
        $this->castlingRule = (new CastlingRule())->getRule();

        $this->attach(new R(Color::W, 'a1', $this->size, RType::CASTLE_LONG));
        $this->attach(new N(Color::W, 'b1', $this->size));
        $this->attach(new A(Color::W, 'c1', $this->size));
        $this->attach(new B(Color::W, 'd1', $this->size));
        $this->attach(new Q(Color::W, 'e1', $this->size));
        $this->attach(new K(Color::W, 'f1', $this->size));
        $this->attach(new B(Color::W, 'g1', $this->size));
        $this->attach(new C(Color::W, 'h1', $this->size));
        $this->attach(new N(Color::W, 'i1', $this->size));
        $this->attach(new R(Color::W, 'j1', $this->size, RType::CASTLE_SHORT));
        $this->attach(new P(Color::W, 'a2', $this->size));
        $this->attach(new P(Color::W, 'b2', $this->size));
        $this->attach(new P(Color::W, 'c2', $this->size));
        $this->attach(new P(Color::W, 'd2', $this->size));
        $this->attach(new P(Color::W, 'e2', $this->size));
        $this->attach(new P(Color::W, 'f2', $this->size));
        $this->attach(new P(Color::W, 'g2', $this->size));
        $this->attach(new P(Color::W, 'h2', $this->size));
        $this->attach(new P(Color::W, 'i2', $this->size));
        $this->attach(new P(Color::W, 'j2', $this->size));
        $this->attach(new R(Color::B, 'a10', $this->size, RType::CASTLE_LONG));
        $this->attach(new N(Color::B, 'b10', $this->size));
        $this->attach(new A(Color::B, 'c10', $this->size));
        $this->attach(new B(Color::B, 'd10', $this->size));
        $this->attach(new Q(Color::B, 'e10', $this->size));
        $this->attach(new K(Color::B, 'f10', $this->size));
        $this->attach(new B(Color::B, 'g10', $this->size));
        $this->attach(new C(Color::B, 'h10', $this->size));
        $this->attach(new N(Color::B, 'i10', $this->size));
        $this->attach(new R(Color::B, 'j10', $this->size, RType::CASTLE_SHORT));
        $this->attach(new P(Color::B, 'a9', $this->size));
        $this->attach(new P(Color::B, 'b9', $this->size));
        $this->attach(new P(Color::B, 'c9', $this->size));
        $this->attach(new P(Color::B, 'd9', $this->size));
        $this->attach(new P(Color::B, 'e9', $this->size));
        $this->attach(new P(Color::B, 'f9', $this->size));
        $this->attach(new P(Color::B, 'g9', $this->size));
        $this->attach(new P(Color::B, 'h9', $this->size));
        $this->attach(new P(Color::B, 'i9', $this->size));
        $this->attach(new P(Color::B, 'j9', $this->size));

        $this->refresh();
    }

    public function play(string $color, string $pgn): bool
    {
        $obj = Move::toObj($color, $pgn, $this->castlingRule);

        return $this->isValidMove($obj) && $this->isLegalMove($obj);
    }
}