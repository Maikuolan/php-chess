<?php

namespace Chess\Variant\Classical\PGN\AN;

use Chess\Exception\UnknownNotationException;
use Chess\Variant\AbstractNotation;

class Color extends AbstractNotation
{
    const W = 'w';
    const B = 'b';

    public function validate(string $color): string
    {
        if ($color === self::W xor $color === self::B) {
            return $color;
        }

        throw new UnknownNotationException();
    }

    public function opp(string $color): string
    {
        if ($color === self::W) {
            return self::B;
        }

        return self::W;
    }
}
