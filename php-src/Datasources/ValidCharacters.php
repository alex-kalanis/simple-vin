<?php

namespace kalanis\simple_vin\Datasources;


use ArrayObject;


/**
 * @extends ArrayObject<string|int, int>
 */
class ValidCharacters extends ArrayObject
{
    public function __construct()
    {
        parent::__construct([
            '0' => 0,
            '1' => 1,
            '2' => 2,
            '3' => 3,
            '4' => 4,
            '5' => 5,
            '6' => 6,
            '7' => 7,
            '8' => 8,
            '9' => 9,
            'X' => 10,
        ]);
    }
}
