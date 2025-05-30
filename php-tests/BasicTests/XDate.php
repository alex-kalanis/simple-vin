<?php

namespace tests\BasicTests;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

class XDate implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('2016-07-28');
    }
}
