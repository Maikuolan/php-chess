<?php

namespace Chess\Variant;

use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;

trait RandomCastlingRuleTrait
{
    protected array $startPos;

    protected array $startFiles;

    protected array $size;

    protected function sq()
    {
        $longCastlingRook = false;
        foreach ($this->startPos as $key => $val) {
            $wSq = chr(97 + $key) . '1';
            $bSq = chr(97 + $key) . $this->size['ranks'];
            if ($val === Piece::R) {
                if (!$longCastlingRook) {
                    $this->rule[Color::W][Piece::R][Castle::LONG]['from'] = $wSq;
                    $this->rule[Color::B][Piece::R][Castle::LONG]['from'] = $bSq;
                    $longCastlingRook = true;
                } else {
                    $this->rule[Color::W][Piece::R][Castle::SHORT]['from'] = $wSq;
                    $this->rule[Color::B][Piece::R][Castle::SHORT]['from'] = $bSq;
                }
            } elseif ($val === Piece::K) {
                $this->rule[Color::W][Piece::K][Castle::LONG]['from'] = $wSq;
                $this->rule[Color::B][Piece::K][Castle::LONG]['from'] = $bSq;
                $this->rule[Color::W][Piece::K][Castle::SHORT]['from'] = $wSq;
                $this->rule[Color::B][Piece::K][Castle::SHORT]['from'] = $bSq;
            }
        }

        return $this;
    }

    protected function moveSqs()
    {
        $kPath = $this->path(
            $this->rule[Color::W][Piece::K][Castle::SHORT]['from'],
            $this->rule[Color::W][Piece::K][Castle::SHORT]['to']
        );

        $rPath = $this->path(
            $this->rule[Color::W][Piece::R][Castle::SHORT]['from'],
            $this->rule[Color::W][Piece::R][Castle::SHORT]['to']
        );

        $path = array_unique([...$kPath, ...$rPath]);

        $this->rule[Color::W][Piece::K][Castle::SHORT]['attack'] = $kPath;
        $this->rule[Color::W][Piece::K][Castle::SHORT]['free'] = array_diff($path, [
            $this->rule[Color::W][Piece::K][Castle::SHORT]['from'],
            $this->rule[Color::W][Piece::R][Castle::SHORT]['from'],
        ]);

        $kPath = $this->path(
            $this->rule[Color::W][Piece::K][Castle::LONG]['from'],
            $this->rule[Color::W][Piece::K][Castle::LONG]['to']
        );

        $rPath = $this->path(
            $this->rule[Color::W][Piece::R][Castle::LONG]['from'],
            $this->rule[Color::W][Piece::R][Castle::LONG]['to']
        );

        $path = array_unique([...$kPath, ...$rPath]);

        $this->rule[Color::W][Piece::K][Castle::LONG]['attack'] = $kPath;
        $this->rule[Color::W][Piece::K][Castle::LONG]['free'] = array_diff($path, [
            $this->rule[Color::W][Piece::K][Castle::LONG]['from'],
            $this->rule[Color::W][Piece::R][Castle::LONG]['from'],
        ]);

        $kPath = $this->path(
            $this->rule[Color::B][Piece::K][Castle::SHORT]['from'],
            $this->rule[Color::B][Piece::K][Castle::SHORT]['to']
        );

        $rPath = $this->path(
            $this->rule[Color::B][Piece::R][Castle::SHORT]['from'],
            $this->rule[Color::B][Piece::R][Castle::SHORT]['to']
        );

        $path = array_unique([...$kPath, ...$rPath]);

        $this->rule[Color::B][Piece::K][Castle::SHORT]['attack'] = $kPath;
        $this->rule[Color::B][Piece::K][Castle::SHORT]['free'] = array_diff($path, [
            $this->rule[Color::B][Piece::K][Castle::SHORT]['from'],
            $this->rule[Color::B][Piece::R][Castle::SHORT]['from'],
        ]);

        $kPath = $this->path(
            $this->rule[Color::B][Piece::K][Castle::LONG]['from'],
            $this->rule[Color::B][Piece::K][Castle::LONG]['to']
        );

        $rPath = $this->path(
            $this->rule[Color::B][Piece::R][Castle::LONG]['from'],
            $this->rule[Color::B][Piece::R][Castle::LONG]['to']
        );

        $path = array_unique([...$kPath, ...$rPath]);

        $this->rule[Color::B][Piece::K][Castle::LONG]['attack'] = $kPath;
        $this->rule[Color::B][Piece::K][Castle::LONG]['free'] = array_diff($path, [
            $this->rule[Color::B][Piece::K][Castle::LONG]['from'],
            $this->rule[Color::B][Piece::R][Castle::LONG]['from'],
        ]);

        sort($this->rule[Color::W][Piece::K][Castle::SHORT]['free']);
        sort($this->rule[Color::W][Piece::K][Castle::LONG]['free']);
        sort($this->rule[Color::B][Piece::K][Castle::SHORT]['free']);
        sort($this->rule[Color::B][Piece::K][Castle::LONG]['free']);
    }

    protected function path(string $from, string $to)
    {
        $path = [];
        $i = ord($from[0]);
        $j = ord($to[0]);
        $min = min($i, $j);
        $max = max($i, $j);
        for ($min; $min <= $max; $min++) {
            $file = chr($min);
            $path[] = $file . $from[1];
        }

        return $path;
    }
}
