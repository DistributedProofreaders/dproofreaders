# Unicode support

The DP codebase fully supports UTF-8, in the HTML rendered to the user as well
as in page texts.

Rather than open up the floodgates and allow proofreaders to input any of
the millions of Unicode character into the proofreading interface, we allow
project managers to restrict what Unicode characters are valid for project
pages. This ensures that proofreaders don't insert characters that don't
rightfully belong in the project but look similar to those that do (Kappa
for a 'K' as an example).

Both the UI and the server-side normalization enforce that only supported
characters are used for project pages. This allows the code to support the full
set of Unicode characters but reduces the possibility of invalid characters in
a given project.

Project managers select which characters are valid for a project by associating
it with a Character Suite when creating or editing the project.

Character suites are only used to enforce project page texts. Project metadata,
such as title and author, are not restricted and will accept any valid Unicode
character. It's recommended not to use Unicode characters that are encoded as 4
bytes in UTF-8 in project titles or authors. These fields are used when creating
project discussion threads and phpBB doesn't support UTF-8 characters encoded
with more than 3 bytes. Using such characters will result in an error when a
user attempts to create a discussion thread.

## Character Suites

Character Suites (also known as charsuites within the code) are collections of
Unicode characters -- individual codepoints or combined characters defined in
the code (see `../pinc/charsuite-basic-latin.inc` for an example). Projects can
use one or more character suites; valid characters are the superset of all
characters in all character suites associated with the project.

Character suites may overlap -- that is a character may exist in multiple
character suites.

Character suites also define Picker Sets. These are collections of characters
that are shown to the users in the proofreading interface character picker.
Picker sets are optional and, if they are used, need not cover the entire set
of codepoints in the character suite.

The code currently ships with 4 character suites:

* Basic Latin - characters contained within ISO-8859-1 and CP-1252
* Extended European Latin
* Basic Greek
* Polytonic Greek

All character suites can be viewed using the [All Character Suites](../tools/charsuites.php)
page.

Character suites are defined by code in `../pinc/`, one file per suite -- for
example: [charsuite-basic-latin.inc](../pinc/charsuite-basic-latin.inc). All
files in `../pinc/` matching `charsuite-*.inc` are loaded automatically.

Character suites are enabled or disabled by site administrators using the
[Manage Site Character Suites](../tools/site_admin/manage_site_charsuites.php)
tool. Character suites that are enabled can be added to new projects as they
are created. Disabled character suites can still be used by projects that are
using them, but cannot be added to new projects.

If you want all new projects to use one or more character suites by default,
set `_DEFAULT_CHAR_SUITES` in your `configuration.sh` file.

## Bypassing Character Suites

While there is no way to bypass a character suite, it is possible to create one
which includes a large swath of the Unicode range. This would effectively bypass
validation as all characters in the range would be valid. This is not recommended
as-is because it includes undefined codepoints, but may be desired for some sites.

For example, to allow all characters in the Basic Multilingual Plane, the following
file could be created in `../pinc/charsuite-bmp.inc` and then enabled.

```php
<?php
include_once($relPath."CharSuites.inc");

$charsuite = new CharSuite("bmp", _("Basic Multilingual Plane"));
$charsuite->codepoints = [
    # skip the C0 control block
    'U+0020-U+FFFF',
];
$charsuite->reference_urls = [
    "https://en.wikipedia.org/wiki/Plane_(Unicode)#Basic_Multilingual_Plane",
];

CharSuites::add($charsuite);
```

## Upgrading from Latin-1 projects

`upgrade/14` includes scripts that will convert a prior Latin-1-based
version of the DP database to support UTF-8. Like any upgrade, you are **strongly
encouraged** to do a full database backup first. It is also a good idea to
put the site into maintenance mode before continuing.

After you've done a full database backup, change the main database's default
character set to `utf8mb4`. Connect to the database as the root user and run:

```sql
ALTER DATABASE <<DATABASE_NAME>> DEFAULT CHARACTER SET utf8mb4;
```

Then run the `upgrade/14` scripts in alphabetical order. If you've put the
site into maintenance mode, you will need to edit each script first and add
the following line after the `<?php` tag and before `base.inc` to bypass the
maintenance check:

```php
$maintenance_override = TRUE;
```

Upgrade scripts related to the Unicode conversion:

* `20190818_convert_db_to_utf8mb4.php`
* `20190819_convert_project_tables_to_utf8mb4.php`
* `20191211_convert_project_text_files.php`
* `20191211_convert_word_lists.php`
* `20200127_add_charsuite_tables.php`
* `20200404_normalize_project_details.php`
* `20200511_convert_archive_db_to_utf8mb4.php`

If, after the upgrade, you need to convert individual project tables to UTF-8,
use the "Convert Project Table to UTF-8" site administrator tool under
`tools/site_admin/convert_project_table_utf8.php`.

## Fonts

The code includes DejaVu Sans Mono, a member of the
[DejaVu font family](https://dejavu-fonts.github.io/). This font includes a 
wide range of Unicode characters and is the default font for new users. It is
also the fallback if users select a different proofreading font to ensure that
characters have an available monospace representation to present if the
selected proofreading font does not.

## Other resources

The following pages at pgdp.net may be useful to you as well:

* [DP Code - Unicode](https://www.pgdp.net/wiki/DP_Code_-_Unicode)
* [Site conversion to Unicode](https://www.pgdp.net/wiki/Site_conversion_to_Unicode)

