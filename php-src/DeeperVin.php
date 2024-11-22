<?php

namespace kalanis\simple_vin;


use kalanis\simple_vin\Support\Manufacturer;
use Psr\Clock\ClockInterface;


/**
 * Extended class for accessing VIN data
 */
class DeeperVin
{
    protected readonly SimpleVin $simple;
    protected readonly Datasources\WorldManufacturerClasses $worldManufacturerIdentifiers;

    public function __construct(
        ?ClockInterface $clock = null,
    )
    {
        // no DI
        $this->simple = new SimpleVin($clock);
        $this->worldManufacturerIdentifiers = new Datasources\WorldManufacturerClasses();
    }

    /**
     * @param string $vinOrWmi
     * @return Manufacturer[]
     */
    public function getWorldManufacturer(string $vinOrWmi): array
    {
        if (!$this->simple->isValid($vinOrWmi)) {
            return [];
        }

        return $this->filterByYears(
            $this->simple->getModelYear($vinOrWmi),
            $this->getByCode($vinOrWmi)
        );
    }

    /**
     * @param string $code
     * @return Manufacturer[]
     */
    protected function getByCode(string $code): array
    {
        $used = [];
        foreach ($this->worldManufacturerIdentifiers as $item) {
            if (is_object($item) && is_a($item, Manufacturer::class) && str_starts_with($code, $item->code)) {
                $used[] = $item;
            }
        }
        return $used;
    }

    /**
     * Years matters only for americans
     * @param int|null $year
     * @param Manufacturer[] $availableManufacturers
     * @return Manufacturer[]
     */
    protected function filterByYears(?int $year, array $availableManufacturers): array
    {
        if (is_null($year)) {
            return $this->filterLongest($availableManufacturers);
        }

        // years from
        $passedFrom = [];
        foreach ($availableManufacturers as $availableManufacturer) {
            if (!$availableManufacturer->canCompareDates) {
                $passedFrom[] = $availableManufacturer;
                continue;
            }
            if (is_null($availableManufacturer->from) || ($availableManufacturer->from <= $year)) {
                $passedFrom[] = $availableManufacturer;
            }
        }

        // years to
        $passedTo = [];
        foreach ($passedFrom as $availableManufacturer) {
            if (!$availableManufacturer->canCompareDates) {
                $passedTo[] = $availableManufacturer;
                continue;
            }
            if (is_null($availableManufacturer->to) || ($availableManufacturer->to >= $year)) {
                $passedTo[] = $availableManufacturer;
            }
        }

        return $this->filterLongest($passedTo);
    }

    /**
     * @param Manufacturer[] $entries
     * @return Manufacturer[]
     */
    protected function filterLongest(array $entries): array
    {
        $longestCodeLen = 0;
        foreach ($entries as $item) {
            $longestCodeLen = max($longestCodeLen, strlen($item->code));
        }
        $onlyLongest = [];
        foreach ($entries as $item) {
            if (strlen($item->code) == $longestCodeLen) {
                $onlyLongest[] = $item;
            }
        }

        return $onlyLongest;
    }
}
