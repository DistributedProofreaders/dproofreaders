# Tests

This directory contains a variety of files used for testing.
Including:
* [phpunit](https://phpunit.de/) tests in the `unittests` directory
* [QUnit](https://qunitjs.com/) tests in the `jsTests` directory
* Manual web-based tests in the `manual_web` directory
* Smoke tests in the `smoketests` directory
* [GitHub Actions](https://github.com/features/actions) configuration

## phpunit tests

`phpunit.xml` contains configuration information like bootstrap
requirements, so running the full suite is as simple as:
```bash
cd unittests
../../../vendor/bin/phpunit
```

To run the page compare test only:
```bash
../../../vendor/bin/phpunit PageCompareTest.php
```

## JS tests

The QUnit-based JS tests can be run from the base of the repo with:
```bash
npm run test
```

You can also load the `SETUP/tests/jsTests/qunit.html` file in your web browser
to run them and see any failures.
