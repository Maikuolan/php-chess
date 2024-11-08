<?php

namespace Chess\Variant\Classical\Piece;

use Chess\Variant\AbstractLinePiece;
use Chess\Variant\RType;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;

class Q extends AbstractLinePiece
{
    public function __construct(string $color, string $sq, Square $square)
    {
        parent::__construct($color, $sq, $square, Piece::Q);

        $this->mobility = [
            ...(new R($color, $sq, $square, RType::R))->mobility,
            ...(new B($color, $sq, $square))->mobility,
        ];
    }
}
