# Simple VIN

![Build Status](https://github.com/alex-kalanis/simple-vin/actions/workflows/code_checks.yml/badge.svg)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-kalanis/simple-vin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-kalanis/simple-vin/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/alex-kalanis/simple-vin/v/stable.svg?v=1)](https://packagist.org/packages/alex-kalanis/simple-vin)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)](https://php.net/)
[![Downloads](https://img.shields.io/packagist/dt/alex-kalanis/simple-vin.svg?v1)](https://packagist.org/packages/alex-kalanis/simple-vin)
[![License](https://poser.pugx.org/alex-kalanis/simple-vin/license.svg?v=1)](https://packagist.org/packages/alex-kalanis/simple-vin)
[![Code Coverage](https://scrutinizer-ci.com/g/alex-kalanis/simple-vin/badges/coverage.png?b=master&v=1)](https://scrutinizer-ci.com/g/alex-kalanis/simple-vin/?branch=master)

This is a set of PHP libraries for **V**ehicle **I**dentification **N**umbers.

Ported from .Net library [Vin](https://github.com/dalenewman/Vin) .

## PHP installation

```bash
composer.phar require alex-kalanis/simple-vin
```

## API usage

```php
// basic vin check
$lib = new \kalanis\simple_vin\SimpleVin(); // can be set via DI, usually no more dependency need
$x = $lib->isValid('11111111111111111'); // true
$m = $lib->getWorldManufacturer('TMP......D.......'); // Skoda Trolleybuses
$y = $lib->getModelYear('TMP......D.......'); // 2013
$k = $lib->restoreChecksumCharacter('1FTKR1AD_APA11957'); // X
$c = $lib->restoreChecksum('1FTKR1AD_APA11957'); // 1FTKR1ADXAPA11957
```

(Refer to [Composer Documentation](https://github.com/composer/composer/blob/master/doc/00-intro.md#introduction) if you are not
familiar with composer)

## Sources

- [Wikipedia](https://en.wikipedia.org/wiki/Vehicle_identification_number)
- ISO 3779
- ISO 4030

## Changes

- v1.0.0 - Initial port and refactor with extended tests
- v1.1.0 - Calculate checksum characters from the rest of code
- v1.2.0 - Update VIN manufacturer codes in accordance with the current Wikipedia page
- v2.0.0 - Update VIN manufacturer format, update minimal php version
