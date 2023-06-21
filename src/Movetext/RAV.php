<?php

namespace Chess\Movetext;

/**
 * Recursive Annotation Variation.
 *
 * @license GPL
 */
class RAV extends SAN
{
    public function getMain()
    {
        return $this->validate();
    }

    /**
     * Fills the array of PGN moves.
     *
     * @param string $movetext
     */
    protected function fill(string $movetext): void
    {
        foreach (explode(' ', $movetext) as $key => $val) {
            if (preg_match('/^[1-9][0-9]*\.\.\.(.*)$/', $val)) {
                $exploded = explode(self::SYMBOL_ELLIPSIS, $val);
                $this->moves[] = $exploded[1];
            } elseif (preg_match('/^[1-9][0-9]*\.(.*)$/', $val)) {
                $this->moves[] = explode('.', $val)[1];
            } else {
                $this->moves[] = $val;
            }
        }

        $this->moves = array_values(array_filter($this->moves));
    }
}