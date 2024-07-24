<?php

namespace BasicTests;

use CommonTestClass;
use DateTimeImmutable;
use kalanis\simple_vin\SimpleVin;
use Psr\Clock\ClockInterface;

class TestVin extends CommonTestClass
{

    public function test17Ones(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertTrue($lib->isValid('11111111111111111'), 'Must pass because all ones pass.');
    }

    public function test16Ones(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertFalse($lib->isValid('1111111111111111'), 'Must fail because it\'s 16 characters.');
    }

    public function test18Ones(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertFalse($lib->isValid('111111111111111111'), 'Must fail because it\'s 18 characters.');
    }

    public function testOneThree(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertFalse($lib->isValid('11111111111111113'), 'Must fail because I changed the last 1 to a 3.');
    }

    public function testBadChecksumPos(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertFalse($lib->isValid('11111111O11111111'), 'Letter O is not defined');
    }

    public function testRealVin(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertTrue($lib->isValid('1FTKR1ADXAPA11957'), 'Must pass because this is a real VIN (I hope)');
    }

    public function testRealVinWithPInsteadOfF(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertFalse($lib->isValid('1ITKR1ADXAPA11957'), 'Must fail because I replaced F with I.');
    }

    public function testInvalidWmi(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEmpty($lib->getWorldManufacturer('111'));
    }

    public function testNoManufacturer(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEmpty($lib->getWorldManufacturer(''));
    }

    public function testInvalidManufacturer(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEmpty($lib->getWorldManufacturer('N'));
    }

    public function testFord(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals('Ford Motor Company', $lib->getWorldManufacturer('1FD'));
    }

    public function testInternational(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals('International', $lib->getWorldManufacturer('1HT'));
    }

    public function testToyotaMatchingFirstTwo(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals('Toyota USA - trucks', $lib->getWorldManufacturer('5TG'));
    }

    public function testGetYear(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals(2001, $lib->getModelYear('1111111111'));
    }

    public function testNoYear(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertNull($lib->getModelYear(''));
    }

    public function testGetYearForA(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals(2010, $lib->getModelYear('111111111A'));
    }

    public function testGetYearForAForceDown(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals(1980, $lib->getModelYear('111111111A', 1980));
    }

    public function testGetYearForAForceUp(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals(2040, $lib->getModelYear('111111111A', 2040, 2044));
    }

    public function testBadStartYear(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertNull($lib->getModelYear('111111111A', 2000));
    }

    public function testGetYearForAForceUp2(): void
    {
        $lib = new SimpleVin(new XDate2());
        $this->assertEquals(2040, $lib->getModelYear('111111111A'));
    }

    public function testGetYearForK(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals(1989, $lib->getModelYear('111111111K'));
    }

    public function testGetYearForD(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals(2013, $lib->getModelYear('111111111D'), 'This should be 2013 because 1983 is less likely (given test is set to 2016).');
    }

    public function testGetYearForDChar(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertEquals(2013, $lib->getModelYear('D'), 'This should be 2013 because 1983 is less likely (given test is set to 2016).');
    }

    public function testInvalidModelYear(): void
    {
        $lib = new SimpleVin(new XDate());
        $this->assertNull($lib->getModelYear('O'));
    }
}


class XDate implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('2016-07-28');
    }
}


class XDate2 implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('2043-07-28');
    }
}
