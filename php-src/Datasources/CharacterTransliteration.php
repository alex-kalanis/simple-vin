<?php

namespace kalanis\simple_vin\Datasources;


use ArrayObject;


/**
 * @extends ArrayObject<string|int, int>
 */
class CharacterTransliteration extends ArrayObject
{
    public function __construct()
    {
        parent::__construct([
            'A' => 1,
            'B' => 2,
            'C' => 3,
            'D' => 4,
            'E' => 5,
            'F' => 6,
            'G' => 7,
            'H' => 8,
            'J' => 1,
            'K' => 2,
            'L' => 3,
            'M' => 4,
            'N' => 5,
            'P' => 7,
            'R' => 9,
            'S' => 2,
            'T' => 3,
            'U' => 4,
            'V' => 5,
            'W' => 6,
            'X' => 7,
            'Y' => 8,
            'Z' => 9,
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            '0' => 0,
        ]);
    }
}
