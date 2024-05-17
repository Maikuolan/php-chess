<?php

namespace Chess\Randomizer\Checkmate;

use Chess\Randomizer\Randomizer;
use Chess\Variant\Classical\PGN\AN\Square;

class TwoBishopsRandomizer extends Randomizer
{
    public function __construct(string $turn)
    {
        $items = [
            $turn => ['B', 'B'],
        ];

        do {
            parent::__construct($turn, $items);
            $colors = '';
            foreach ($this->board->getPieces($turn) as $piece) {
                if ($piece->getId() === 'B') {
                    $colors .= Square::color($piece->getSq());
                }
            }
        } while ($colors === 'ww' || $colors === 'bb');
    }
}