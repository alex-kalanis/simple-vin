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

        $available = $this->worldManufacturerIdentifiers->getByCode(substr($vinOrWmi, 0, 3));
        if ($available) {
            return $this->filterByYears($this->simple->getModelYear($vinOrWmi), $available);
        }

        return $this->filterByYears(
            $this->simple->getModelYear($vinOrWmi),
            $this->worldManufacturerIdentifiers->getByCode(substr($vinOrWmi, 0, 2))
        );
    }

    /**
     * @param int|null $year
     * @param Manufacturer[] $availableManufacturers
     * @return Manufacturer[]
     */
    protected function filterByYears(?int $year, array $availableManufacturers): array
    {
        if (is_null($year)) {
            return $availableManufacturers;
        }

        $passedFrom = [];
        foreach ($availableManufacturers as $availableManufacturer) {
            if (is_null($availableManufacturer->from) || ($availableManufacturer->from <= $year)) {
                $passedFrom[] = $availableManufacturer;
            }
        }
        $passedTo = [];
        foreach ($passedFrom as $availableManufacturer) {
            if (is_null($availableManufacturer->to) || ($availableManufacturer->to >= $year)) {
                $passedTo[] = $availableManufacturer;
            }
        }
        return $passedTo;
    }
}
