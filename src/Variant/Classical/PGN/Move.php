<?php

namespace Chess\Variant\Classical\PGN;

use Chess\Exception\UnknownNotationException;
use Chess\Piece\K;
use Chess\Variant\AbstractNotation;
use Chess\Variant\Classical\PGN\AN\Castle;
use Chess\Variant\Classical\PGN\AN\Check;
use Chess\Variant\Classical\PGN\AN\Color;
use Chess\Variant\Classical\PGN\AN\Piece;
use Chess\Variant\Classical\PGN\AN\Square;
use Chess\Variant\Classical\Rule\CastlingRule;

class Move extends AbstractNotation
{
    const CASTLE_SHORT = Castle::SHORT . Check::REGEX;
    const CASTLE_LONG = Castle::LONG . Check::REGEX;
    const ELLIPSIS = '...';
    const KING = 'K' . Square::REGEX . Check::REGEX;
    const KING_CAPTURES = 'Kx' . Square::REGEX . Check::REGEX;
    const KNIGHT = 'N[a-h]{0,1}[1-8]{0,1}' . Square::REGEX . Check::REGEX;
    const KNIGHT_CAPTURES = 'N[a-h]{0,1}[1-8]{0,1}x' . Square::REGEX . Check::REGEX;
    const PAWN = Square::REGEX . Check::REGEX;
    const PAWN_CAPTURES = '[a-h]{1}x' . Square::REGEX . Check::REGEX;
    const PAWN_PROMOTES = '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQ]{0,1}' . Check::REGEX;
    const PAWN_CAPTURES_AND_PROMOTES = '[a-h]{1}x' . '[a-h]{1}(1|8){1}' . '[=]{0,1}[NBRQ]{0,1}' . Check::REGEX;
    const PIECE = '[BRQ]{1}[a-h]{0,1}[1-8]{0,1}' . Square::REGEX . Check::REGEX;
    const PIECE_CAPTURES = '[BRQ]{1}[a-h]{0,1}[1-8]{0,1}x' . Square::REGEX . Check::REGEX;

    public function cases(): array
    {
        return (new \ReflectionClass(__CLASS__))->getConstants();
    }

    public function case(string $case): string
    {
        $key = array_search($case, $this->cases());

        return $this->cases()[$key];
    }

    public function extractSqs(string $string): string
    {
        return preg_replace(Square::EXTRACT, '', $string);
    }

    public function explodeSqs(string $string): array
    {
        preg_match_all('/'.Square::REGEX.'/', $string, $matches);

        return $matches[0];
    }

    public function validate(string $value): string
    {
        switch (true) {
            case preg_match('/^' . static::PIECE . '$/', $value):
                return $value;
            case preg_match('/^' . static::PIECE_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::KNIGHT . '$/', $value):
                return $value;
            case preg_match('/^' . static::KNIGHT_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::KING . '$/', $value):
                return $value;
            case preg_match('/^' . static::KING_CAPTURES . '$/', $value):
                return $value;
            case preg_match('/^' . static::CASTLE_SHORT . '$/', $value):
                return $value;
            case preg_match('/^' . static::CASTLE_LONG . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_PROMOTES . '$/', $value):
                return $value;
            case preg_match('/^' . static::PAWN_CAPTURES_AND_PROMOTES . '$/', $value):
                return $value;
        }

        throw new UnknownNotationException();
    }

    public function toArray(string $str, string $pgn, CastlingRule $castlingRule = null, Color $color): array
    {
        if (preg_match('/^' . static::PIECE . '$/', $pgn)) {
            $sqs = $this->extractSqs($pgn);
            $to = substr($sqs, -2);
            $from = str_replace($to, '', $sqs);
            return [
                'pgn' => $pgn,
                'case' => static::PIECE,
                'color' => $color->validate($str),
                'id' => mb_substr($pgn, 0, 1),
                'from' => $from,
                'to' => $to,
            ];
        } elseif (preg_match('/^' . static::PIECE_CAPTURES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'case' => static::PIECE_CAPTURES,
                'color' => $color->validate($str),
                'id' => mb_substr($pgn, 0, 1),
                'from' => $this->extractSqs($arr[0]),
                'to' => $this->extractSqs($arr[1]),
            ];
        } elseif (preg_match('/^' . static::PAWN . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::PAWN,
                'color' => $color->validate($str),
                'id' => Piece::P,
                'from' => mb_substr($pgn, 0, 1),
                'to' => $this->extractSqs($pgn),
            ];
        } elseif (preg_match('/^' . static::PAWN_CAPTURES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'case' => static::PAWN_CAPTURES,
                'color' => $color->validate($str),
                'id' => Piece::P,
                'from' => mb_substr($pgn, 0, 1),
                'to' => $this->extractSqs($arr[1]),
            ];
        } elseif (preg_match('/^' . static::KNIGHT . '$/', $pgn)) {
            $sqs = $this->extractSqs($pgn);
            $to = substr($sqs, -2);
            $from = str_replace($to, '', $sqs);
            return [
                'pgn' => $pgn,
                'case' => static::KNIGHT,
                'color' => $color->validate($str),
                'id' => Piece::N,
                'from' => $from,
                'to' => $to,
            ];
        } elseif (preg_match('/^' . static::KNIGHT_CAPTURES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'case' => static::KNIGHT_CAPTURES,
                'color' => $color->validate($str),
                'id' => Piece::N,
                'from' => $this->extractSqs($arr[0]),
                'to' => $this->extractSqs($arr[1]),
            ];
        } elseif (preg_match('/^' . static::KING . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::KING,
                'color' => $color->validate($str),
                'id' => Piece::K,
                'from' => '',
                'to' => $this->extractSqs($pgn),
            ];
        } elseif (preg_match('/^' . static::KING_CAPTURES . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::KING_CAPTURES,
                'color' => $color->validate($str),
                'id' => Piece::K,
                'from' => '',
                'to' => $this->extractSqs($pgn),
            ];
        } elseif (preg_match('/^' . static::CASTLE_SHORT . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::CASTLE_SHORT,
                'color' => $color->validate($str),
                'id' => Piece::K,
                'from' => $castlingRule?->rule[$str][Piece::K][Castle::SHORT]['from'],
                'to' => $castlingRule?->rule[$str][Piece::K][Castle::SHORT]['to'],
            ];
        } elseif (preg_match('/^' . static::CASTLE_LONG . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::CASTLE_LONG,
                'color' => $color->validate($str),
                'id' => Piece::K,
                'from' => $castlingRule?->rule[$str][Piece::K][Castle::LONG]['from'],
                'to' => $castlingRule?->rule[$str][Piece::K][Castle::LONG]['to'],
            ];
        } elseif (preg_match('/^' . static::PAWN_PROMOTES . '$/', $pgn)) {
            return [
                'pgn' => $pgn,
                'case' => static::PAWN_PROMOTES,
                'color' => $color->validate($str),
                'id' => Piece::P,
                'newId' => substr(explode('=', $pgn)[1], 0, 1),
                'from' => '',
                'to' => $this->extractSqs($pgn),
            ];
        } elseif (preg_match('/^' . static::PAWN_CAPTURES_AND_PROMOTES . '$/', $pgn)) {
            $arr = explode('x', $pgn);
            return [
                'pgn' => $pgn,
                'case' => static::PAWN_CAPTURES_AND_PROMOTES,
                'color' => $color->validate($str),
                'id' => Piece::P,
                'newId' => substr(explode('=', $pgn)[1], 0, 1),
                'from' => '',
                'to' => $this->extractSqs($arr[1]),
            ];
        }

        throw new UnknownNotationException($pgn);
    }
}
