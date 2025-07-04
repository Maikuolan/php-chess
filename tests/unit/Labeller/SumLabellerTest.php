<?php

namespace Chess\Tests\Unit\Labeller;

use Chess\SanHeuristics;
use Chess\Function\CompleteFunction;
use Chess\Labeller\SumLabeller;
use Chess\Play\SanPlay;
use Chess\Tests\AbstractUnitTestCase;

class SumLabellerTest extends AbstractUnitTestCase
{
    static private CompleteFunction $f;

    public static function setUpBeforeClass(): void
    {
        self::$f = new CompleteFunction();
    }

    /**
     * @test
     */
    public function A00()
    {
        $name = 'Material';

        $A00 = file_get_contents(self::DATA_FOLDER.'/sample/A00.pgn');

        $board = (new SanPlay($A00))->validate()->board;

        $time = (new SanHeuristics(self::$f, $board->movetext(), $name))->time;

        $label = (new SumLabeller())->label($time);

        $expected = 0.0;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A08()
    {
        $name = 'Center';

        $A08 = file_get_contents(self::DATA_FOLDER.'/sample/A08.pgn');

        $board = (new SanPlay($A08))->validate()->board;

        $time = (new SanHeuristics(self::$f, $board->movetext(), $name))->time;

        $label = (new SumLabeller())->label($time);

        $expected = 0.24;

        $this->assertSame($expected, $label);
    }

    /**
     * @test
     */
    public function A59()
    {
        $name = 'Connectivity';

        $A59 = file_get_contents(self::DATA_FOLDER.'/sample/A59.pgn');

        $board = (new SanPlay($A59))->validate()->board;

        $time = (new SanHeuristics(self::$f, $board->movetext(), $name))->time;

        $label = (new SumLabeller())->label($time);

        $expected = 2.0;

        $this->assertSame($expected, $label);
    }
}
