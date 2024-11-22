<?php

namespace kalanis\simple_vin\Datasources;


use ArrayObject;


/**
 * @extends ArrayObject<string|int, int>
 */
final class Years extends ArrayObject
{
    public function __construct()
    {
        parent::__construct([
            'A' => 0,
            'B' => 1,
            'C' => 2,
            'D' => 3,
            'E' => 4,
            'F' => 5,
            'G' => 6,
            'H' => 7,
            'J' => 8,
            'K' => 9,
            'L' => 10,
            'M' => 11,
            'N' => 12,
            'P' => 13,
            'R' => 14,
            'S' => 15,
            'T' => 16,
            'V' => 17,
            'W' => 18,
            'X' => 19,
            'Y' => 20,
            '1' => 21,
            '2' => 22,
            '3' => 23,
            '4' => 24,
            '5' => 25,
            '6' => 26,
            '7' => 27,
            '8' => 28,
            '9' => 29,
        ]);
    }

    public function checkStart(int $passed): bool
    {
        return in_array($passed, [1980, 2010, 2040,]);
    }
}
