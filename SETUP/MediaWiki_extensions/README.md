# MediaWiki Extensions
These MediaWiki extensions enable the use of Magic Tags to show project
information on a wiki page. These extensions support MediaWiki 1.32 and later.

## Requirements
These assume that the MediaWiki tables and the DP tables are running in the
same MySQL server and use the same authentication, but they do not have to
be in the same database itself.

## Installation
To use them:

1. Copy the files to your MediaWiki `extensions/` directory
2. Edit the files and update `$relPath` to point to your DP `c/pinc/` directory
3. Add the following lines to your MediaWiki `LocalSettings.php` file:
```php
    require_once('extensions/dpExtensions.php');
    require_once('extensions/hospitalExtensions.php');
```

## Magic Tags provided
The following magic tags are provided:

* `projectinfo`
* `pg_formats`
* `hospital_info`

See <http://www.pgdp.net/wiki/DPWiki:Magic_tags> for an example of how
these are used at DP.
