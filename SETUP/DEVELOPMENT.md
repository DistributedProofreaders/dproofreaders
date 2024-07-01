# Code Development

This doc is designed for users who want to develop on the DProofreaders (DP)
codebase.

## Introduction

The DP code is almost entirely server-side PHP with a smattering of JS and
CSS. It uses a MySQL backend database and requires a web server -- pgdp.net
uses Apache but Nginx should work as well. See the [installation docs](INSTALL.md)
for information on minimum middleware versions.

The code does not use a PHP framework or an ORM. It was developed 20 years ago
and has grown very organically over the years. There has been effort in the
past decade to standardize things but you'll find many different patterns remain.

## Development Environment

The best current development environment is using the
[DP development VM](https://www.pgdp.net/wiki/DP_Code_Development_VM) which
comes with a recent release of the DP code, all required middleware, and some
sample data. There is a desire to get a true local development environment --
via vagrant or docker -- created but that has not yet been completed.

### Tooling

The code uses [Composer](https://getcomposer.org/) to manage PHP dependencies.
Some of these dependencies are runtime dependencies, others are just used for
development (phpstan, phpunit, etc). To install PHP dependencies (runtime
and development) run the following in the repo base:
```bash
composer install
```

The code also uses [npm](https://www.npmjs.com/) for some _development only_
dependencies, such as linting (eslint) and CSS creation (less). To install
nodejs dependencies (runtime and development) run the following in the repo base:
```bash
npm install
```

## Development-related docs

* [coding standards](CODE_STYLE.md)
* [coding best practices](https://www.pgdp.net/wiki/DP_Code_Best_Practices)
* [automated tests](tests/README.md)
* [CSS / style documentation](../style/README.md)
* [API documentation](../api/README.md)
* [coding documentation](CODE_DOCS.md)
* [graphs design](GRAPHS.md)
* [Unicode support](UNICODE.md)

## Organizing principals

The code is loosely organized around the following ideas:
* PHP pages end in `.php` extensions. As site entry points, all `.php` pages
  need to include `pinc/base.inc` which sets up common infrastructure like
  database connections, gettext for localization, error handlers, and more.
* PHP code that is included in PHP pages end in `.inc` extensions. Many of
  these live in the `pinc/` directory -- short for *P*hp *INC*lude.
  * 3rd party code that we distribute but (generally) do not modify is contained
    in `pinc/3rdparty/`. See the [3rdparty readme](../pinc/3rdparty/README.md).
* Strings are localized by using gettext. These are wrapped in a `_()` function
  that the localization tooling will extract into `.po` files that volunteer
  translators will translate. We do not have any official change control over
  translated strings, just update them as necessary. See the
  [translation best practices](https://www.pgdp.net/wiki/DP_Code_Best_Practices#String_localization).
* CSS scripts are in `styles/` which include `.less` source files and the
  rendered `.css` files. See [CSS / style documentation](../style/README.md).
* Non-runtime code, such as site admin docs, development docs, tests, tooling
  and other miscellanea are in `SETUP/`. The expectations is that this directory
  will not, and should not be, accessible via the web context on a live site.
  * Upgrade scripts are in `SETUP/upgrade/`. Each release gets a new subdir
    where we collect upgrade scripts for site admins to run when they install
    a new release. See also [UPGRADE](UPGRADE.md).
  * Tests, mostly automated but some manual, are in `SETUP/tests`. See the
    [tests README](tests/README.md).
  * CI scripts that are run as part of Github Actions -- and can be run manually --
    are in `SETUP/ci`. See `.github/workflows/ci.yml` for the GHA workflows.
