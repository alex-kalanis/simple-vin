<?php

namespace kalanis\simple_vin;


use DateTime;
use Psr\Clock\ClockInterface;


/**
 * Basic class for accessing VIN data
 */
class SimpleVin
{
    protected const VALID_VIN_LENGTH = 17;
    protected const CHECK_INDEX_ON_DIGIT = 8;
    // Character weights for 17 characters in VIN
    /** @var int[] */
    protected static array $CharacterWeights = [8, 7, 6, 5, 4, 3, 2, 10, 0, 9, 8, 7, 6, 5, 4, 3, 2];

    protected int $startYear;
    protected int $nextYear;

    protected Datasources\Years $years;
    protected Datasources\ValidCharacters $validCheckCharacters;
    protected Datasources\CharacterTransliteration $characterTransliteration;
    protected Datasources\WorldManufacturerIdentifiers $worldManufacturerIdentifiers;

    public function __construct(
        ?ClockInterface $clock = null
    )
    {
        // no DI
        $this->years = new Datasources\Years();
        $this->validCheckCharacters = new Datasources\ValidCharacters();
        $this->characterTransliteration = new Datasources\CharacterTransliteration();
        $this->worldManufacturerIdentifiers = new Datasources\WorldManufacturerIdentifiers();

        $clocks = $clock ? $clock->now() : new DateTime(); // because it has not a correct interface!

        $this->startYear = intdiv(intval($clocks->format('Y')), 30) * 30; // base year
        $this->nextYear = intval($clocks->format('Y')) + 1; // next year against current date
    }

    public function isValid(string $vin): bool
    {
        if (static::VALID_VIN_LENGTH != strlen($vin)) {
            return false;
        }

        $checkCharacter = $vin[static::CHECK_INDEX_ON_DIGIT];

        $calculated = $this->calculateChecksum($vin);
        if (is_null($calculated)) {
            return false;
        }

        return $calculated == $this->validCheckCharacters[$checkCharacter];
    }

    public function restoreChecksum(string $vin): ?string
    {
        $char = $this->restoreChecksumCharacter($vin);

        if (is_null($char)) {
            return null;
        }

        $vin[static::CHECK_INDEX_ON_DIGIT] = $char;
        return $vin;
    }

    public function restoreChecksumCharacter(string $vin): ?string
    {
        if (static::VALID_VIN_LENGTH != strlen($vin)) {
            return null;
        }

        $calculated = $this->calculateChecksum($vin);
        if (is_null($calculated)) {
            return null;
        }

        $chars = array_map('strval', array_flip($this->validCheckCharacters->getArrayCopy()));

        return $chars[$calculated] ?? null;
    }

    protected function calculateChecksum(string $vin): ?int
    {
        $value = 0;

        for ($i = 0; $i < static::VALID_VIN_LENGTH; $i++) {
            if (empty(static::$CharacterWeights[$i])) {
                continue;
            }
            if (!isset($this->characterTransliteration[$vin[$i]])) {
                return null;
            }
            $value += (static::$CharacterWeights[$i] * ($this->characterTransliteration[$vin[$i]]));
        }

        return $value % 11;
    }

    public function getWorldManufacturer(string $vinOrWmi): string
    {
        if (empty($vinOrWmi)) {
            return '';
        }

        if (2 > strlen($vinOrWmi)) {
            return '';
        }

        $prefix = substr($vinOrWmi, 0, 3);
        if (strlen($vinOrWmi) > 2 && isset($this->worldManufacturerIdentifiers[$prefix])) {
            return $this->worldManufacturerIdentifiers[$prefix];
        }

        $prefix = substr($vinOrWmi, 0, 2);
        return $this->worldManufacturerIdentifiers[$prefix] ?? '';
    }

    public function getModelYear(string $ident, ?int $startYear = null, ?int $nextYear = null): ?int
    {
        if (empty($ident)) {
            return null;
        }

        if (empty($startYear)) {
            $startYear = $this->startYear;
        }

        if (empty($nextYear)) {
            $nextYear = $this->nextYear;
        }

        if (!$this->years->checkStart($startYear)) {
            return null;
        }

        if (10 > strlen($ident)) {
            return $this->getModelYearByNumber($ident, $startYear, $nextYear);
        }

        return $this->getModelYearByNumber($ident[9], $startYear, $nextYear);
    }

    protected function getModelYearByNumber(string $yearCharacter, int $startYear, int $nextYear): ?int
    {
        if (isset($this->years[$yearCharacter])) {
            $year = $startYear + $this->years[$yearCharacter];
            if ($year > $nextYear) {
                $year -= 30;
            }
            return $year;
        }

        return null;
    }
}
