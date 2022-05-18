# Unit tests

This directory contains a variety of files used for automated testing.
Including:
* [phpunit](https://phpunit.de/) tests in this directory
* [QUnit](https://qunitjs.com/) tests in the `jsTests` directory
* Manual web-based tests in the `manual_web` directory
* [GitHub Actions](https://github.com/features/actions) configuration

## phpunit tests

### Version requirements

The phpunit tests, as-coded, will work with phpunit 6.x, 7.x, 8.x, and 9.x.

### Running tests

`phpunit.xml` contains configuration information like bootstrap
requirements, so running the full suite is as simple as:
```bash
../../vendor/bin/phpunit
```

To run the page compare test only:
```bash
../../vendor/bin/phpunit PageCompareTest.php
```
