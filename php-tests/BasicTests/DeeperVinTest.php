<?php

namespace BasicTests;


use CommonTestClass;
use DateTimeImmutable;
use kalanis\simple_vin\DeeperVin;
use kalanis\simple_vin\SimpleVin;
use kalanis\simple_vin\Support\Manufacturer;
use Psr\Clock\ClockInterface;


class DeeperVinTest extends CommonTestClass
{
    public function testManufacturer(): void
    {
        $lib = Manufacturer::init(
            'foo',
            'bar',
            'baz',
            'bds',
            'bsn',
            'tuj',
            123,
            456,
            'dfhj',
        );
        $this->assertEquals('foo', $lib->code);
        $this->assertEquals('bar', $lib->brand);
        $this->assertEquals('baz', $lib->types);
        $this->assertEquals('bds', $lib->model);
        $this->assertEquals('bsn', $lib->manufacturer);
        $this->assertEquals('tuj', $lib->country);
        $this->assertEquals(123, $lib->from);
        $this->assertEquals(456, $lib->to);
        $this->assertEquals('dfhj', $lib->notes);
        $this->assertEquals('bsn - bar bds baz (123-456), tuj', $lib->desc());
        $this->assertEquals('bsn - bar bds baz (123-456), tuj', strval($lib));
    }

    public function testSimpleChecks(): void
    {
        $lib = new SimpleVin(new XDateDeep());
//print_r(['get1', $lib->restoreChecksumCharacter('KF900000200000000')]);
//print_r(['get2', $lib->restoreChecksumCharacter('JS000000000000000')]);
//print_r(['get3', $lib->restoreChecksumCharacter('WDX00000610000000')]);
//print_r(['get4', $lib->restoreChecksumCharacter('WDX00000570000000')]);
//print_r(['get5', $lib->restoreChecksumCharacter('ZCG00000070000000')]);
        $this->assertTrue($lib->isValid('KF900000200000000'), 'Must pass because it\'s correct format.');
        $this->assertTrue($lib->isValid('JS000000000000000'), 'Must pass because it\'s correct format.');
        $this->assertTrue($lib->isValid('WDX00000610000000'), 'Must pass because it\'s correct format.');
        $this->assertTrue($lib->isValid('WDX00000570000000'), 'Must pass because it\'s correct format.');
        $this->assertTrue($lib->isValid('ZCG00000070000000'), 'Must pass because it\'s correct format.');
    }

    public function testManufacturerGetDataFailedNumber(): void
    {
        $lib = new DeeperVin(new XDateDeep());
        $this->assertEmpty($lib->getWorldManufacturer('WDX00000000000000'));
    }

    public function testManufacturerGetDataLongIdentifier(): void
    {
        $lib = new DeeperVin(new XDateDeep());
        $this->assertEquals([0 => [
            'code' => 'KF9',
            'brand' => 'Tomcar',
            'types' => '',
            'model' => '',
            'manufacturer' => 'Tomcar',
            'country' => 'Israel',
            'from' => null,
            'to' => null,
            'notes' => '',
        ]], array_map(function ($data) {
                return (array) $data;
            }, $lib->getWorldManufacturer('KF900000200000000'))
        );
    }

    public function testManufacturerGetDataShortIdentifier(): void
    {
        $lib = new DeeperVin(new XDateDeep());
        $this->assertEquals([0 => [
            'code' => 'JS',
            'brand' => 'Suzuki',
            'types' => '',
            'model' => '',
            'manufacturer' => 'Suzuki',
            'country' => 'Japan',
            'from' => null,
            'to' => null,
            'notes' => '',
        ]], array_map(function ($data) {
                return (array) $data;
            }, $lib->getWorldManufacturer('JS000000000000000'))
        );
    }

    public function testManufacturerGetDataEmptyYear(): void
    {
        $lib = new DeeperVin(new XDateDeep());
        $this->assertEmpty($lib->getWorldManufacturer('WDX00000610000000'));
    }

    public function testManufacturerGetDataKnownYear(): void
    {
        $lib = new DeeperVin(new XDateDeep());
        $this->assertEquals([0 => [
            'code' => 'WDX',
            'brand' => 'Dodge',
            'types' => 'incomplete vehicle',
            'model' => 'Sprinter',
            'manufacturer' => '',
            'country' => 'Germany',
            'from' => 2005,
            'to' => 2009,
            'notes' => '',
        ]], array_map(function ($data) {
                return (array) $data;
            }, $lib->getWorldManufacturer('WDX00000570000000'))
        );
    }

    public function testManufacturerGetMultipleData(): void
    {
        $lib = new DeeperVin(new XDateDeep());
        $this->assertEquals([[
            'code' => 'ZCG',
            'brand' => '',
            'types' => '',
            'model' => '',
            'manufacturer' => 'Cagiva SpA',
            'country' => 'Italy',
            'from' => null,
            'to' => null,
            'notes' => '',
        ], [
            'code' => 'ZCG',
            'brand' => '',
            'types' => '',
            'model' => '',
            'manufacturer' => 'MV Agusta',
            'country' => 'Italy',
            'from' => null,
            'to' => null,
            'notes' => '',
        ]], array_map(function ($data) {
                return (array) $data;
            }, $lib->getWorldManufacturer('ZCG00000070000000'))
        );
    }
}


class XDateDeep implements ClockInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable('2016-07-28');
    }
}
