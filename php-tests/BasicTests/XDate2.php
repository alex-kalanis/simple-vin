<?php

namespace tests\BasicTests;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

class XDate2 implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('2043-07-28');
    }
}
