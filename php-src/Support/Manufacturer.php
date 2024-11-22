<?php

namespace kalanis\simple_vin\Support;


final class Manufacturer implements \Stringable
{
    public function __construct(
        public readonly string $code,
        public readonly string $brand,
        public readonly string $types = '',
        public readonly string $model = '',
        public readonly string $manufacturer = '',
        public readonly string $country = '',
        public readonly ?int   $from = null,
        public readonly ?int   $to = null,
        public readonly string $notes = '',
        public readonly bool   $canCompareDates = false,
    )
    {
    }

    public function desc(): string
    {
        return
            ($this->manufacturer ? $this->manufacturer . ' - ' : '')
            . $this->brand . ' ' . $this->model
            . ($this->types ? (' ' . $this->types) : '')
            . (($this->from || $this->to) ? (' (' . ($this->from ?: '') . '-' . ($this->to ?: '') . ')') : '')
            . ($this->country ? ', ' . $this->country : '');
    }

    public function __toString(): string
    {
        return $this->desc();
    }
}
