<?php

namespace Chess\Tests\Unit\Randomizer\Checkmate;

use Chess\Randomizer\Checkmate\TwoBishopsRandomizer;
use Chess\Tests\AbstractUnitTestCase;
use Chess\Variant\Classical\PGN\AN\Color;

class TwoBishopsRandomizerTest extends AbstractUnitTestCase
{
    /**
     * @test
     */
    public function w_get_board()
    {
        $board = (new TwoBishopsRandomizer($turn = Color::W))->getBoard();

        $this->assertNotEmpty($board->toFen());
    }

    /**
     * @test
     */
    public function b_get_board()
    {
        $board = (new TwoBishopsRandomizer($turn = Color::B))->getBoard();

        $this->assertNotEmpty($board->toFen());
    }
}