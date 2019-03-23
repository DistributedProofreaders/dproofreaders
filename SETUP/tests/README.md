# Unit tests

These are phpunit tests.

## Version requirements

The phpunit tests, as-coded, will work with phpunit 5.7, 6.x, and 7.x. They
will not work in 8.x or later because the setUp() and tearDown() functions
do not specify void return types. (See the
[phpunit8 release notes](https://phpunit.de/announcements/phpunit-8.html).)
That capability isn't available until PHP 7.1, which the DP code will
nominally work with, but isn't available on the TEST server or in many
modern distros.

## Running tests

To run them, use the `phpunit_bootstrap.php` file to pull in pre-requisits.

    phpunit --bootstrap phpunit_bootstrap.php --verbose .

To run the page compare test only:

    phpunit PageCompareTest.php
