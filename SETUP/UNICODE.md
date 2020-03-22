# Unicode support

The DP codebase fully supports UTF-8, both in the HTML rendered to the user
as well as in page texts.

Rather than open up the floodgates and allow proofreaders to input any of
the millions of Unicode character into the proofreading interface, we allow
project managers to restrict what Unicode characters are valid for a project.
This ensures that proofreaders don't insert characters that don't rightfully
belong in the project but look similar to those that do (Kappa for a 'K'
as an example).

Both the UI and the server-side normalization enforce that only supported
characters are used. This allows the code to support the full set of Unicode
characters but reduces the possibility of invalid characters in a given project.

Project managers select which characters are valid for a project by associating
it with a Character Suite when creating or editing the project.

## Character Suites

Character Suites (sometimes referred to as charsuites) are a collection of
Unicode characters -- individual codepoints or combined characters defined in
the code (see `../pinc/charsuite-basic-latin.inc` for an example). Projects can
use one or more character suites; valid characters are the superset of all
characters in all character suites associated with the project.

Character suites may overlap -- that is a character may exist in multiple
character suites.

Character suites also define Picker Sets. These are a collection of characters
that are shown to the user in the proofreading interface character picker.

The code currently ships with 4 character suites:

* Basic Latin - characters contained within ISO-8859-1 and CP-1252
* Extended European Latin
* Basic Greek
* Polytonic Greek

All character suites can be viewed using the [All Character Suites](tools/charsuites.php)
page.

Character suites are enabled or disabled by site administrators using the
[Manage Site Character Suites](../tools/site_admin/manage_site_charsuites.php)
tool. Character suites that are enabled can be added to new projects as they
are created. Disabled character suites can still be used by projects that are
using them, but cannot be added to new projects.

If you want all new projects to use one or more character suites by default,
set `_DEFAULT_CHAR_SUITES` in your `configuration.sh` file.

## Upgrading from Latin-1 projects

`upgrade/14` includes scripts that will convert a prior Latin-1-based
version of the DP database to support UTF-8.

## Other resources

The following pages at pgdp.net may be useful to you as well:

* [DP Code - Unicode](https://www.pgdp.net/wiki/DP_Code_-_Unicode)
* [Site conversion to Unicode](https://www.pgdp.net/wiki/Site_conversion_to_Unicode)

