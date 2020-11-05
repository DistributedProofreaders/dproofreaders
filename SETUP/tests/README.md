# Unit tests

These are phpunit tests.

## Version requirements

The phpunit tests, as-coded, will work with phpunit 6.x, 7.x, 8.x, and 9.x.

## Running tests

To run them, use the `phpunit_bootstrap.php` file to pull in pre-requisits.

    phpunit --bootstrap phpunit_bootstrap.php --verbose .

To run the page compare test only:

    phpunit PageCompareTest.php
