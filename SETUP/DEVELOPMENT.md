# Code Development

This doc is designed for users who want to develop on the DProofreaders (DP)
codebase.

## Introduction

The DP code is almost entirely server-side PHP with a smattering of JS and
CSS. It uses a MySQL backend database and requires a web server -- pgdp.net
uses Apache but Nginx should work as well. See the [installation docs](INSTALL.md)
for information on minimum middleware versions.

The code does not use a PHP framework or an ORM. It was developed 25 years ago
and has grown very organically over the years. There has been effort in the
past decade to standardize things but you'll find many different patterns remain.
We have a defined [coding style](CODE_STYLE.md) for the codebase and a set of
[best practices](https://www.pgdp.net/wiki/DP_Code_Best_Practices) that cover
some of our prescribed patterns.

## Contributing

DP code uses git for source control and is housed at GitHub in the
[DProofreaders/dproofreaders](https://github.com/DistributedProofreaders/dproofreaders)
repository -- likely where you got this code to begin with.

We use the "fork, branch, develop, merge" model, where developers are expected to:
1. fork the main repository
2. create a development branch off master
3. develop code on the branch
4. generate a merge/pull request when ready to merge

Several CI tests will run when the PR is opened, including things like linting,
security checks, phpstan, and more. Most of these can be run locally with:

```bash
# run the base set of linting and static analysis checks
make -C SETUP/ci
```

See also [DP Code Development Using git](https://www.pgdp.net/wiki/DP_Code_Development_Using_git)
and a similar guide specific to the [dproofreaders](https://www.pgdp.net/wiki/DP_Code_Development_Using_git:_dproofreaders)
repo.

## Development Environment

The current development environment is using the [docker compose](devex/README.md)
provided with this repo. If your OS doesn't allow running containers, consider
spinning up an Ubuntu-based virtual machine and running the containers within
that.

### Tooling

The code uses [Composer](https://getcomposer.org/) to manage PHP dependencies.
Some of these dependencies are runtime dependencies, others are just used for
development (phpstan, phpunit, etc). To install PHP dependencies (runtime
and development) run the following in the repo base:
```bash
composer install
```

The code also uses [nodejs](https://nodejs.org/) for development dependencies,
such as linting (eslint) and CSS creation (less), and to manage some JS code
used within the browser. Packages are installed with npm that comes with nodejs.

In addition to the
[nodejs download site](https://nodejs.org/en/download/package-manager), Linux
users can find packages for recent nodejs versions from
[nodesource](https://github.com/nodesource/distributions).

To install nodejs dependencies run the following in the repo base:
```bash
npm install
```

### JS development & deployment

tl;dr: To develop JS: Delete `dist/manifest.php` if it exists. Either delete
`dist/*` _or_ run `npm run build` whenever you change JS code.

The site uses JS code only in the browser. This code uses ES6 modules which
work directly in the browser (see [INSTALL.md](INSTALL.md) for the list of
supported browsers). However, effectively using ES6 modules in production
requires bundling the code such that all files have content-based hashes in
the filenames to thwart browser caching when new code is released.

The code uses [webpack](https://webpack.js.org/) to bundle the JS code and
create a JSON manifest file that can be consumed by the PHP code. The JSON
manifest file can then optionally be converted into a PHP file which is cached
in the PHP opcache making it very fast and efficient.

That means there are three possible ways the JS code can be served to a client:
* as an ES6 modules directly in the code (works for development, does not work
  for production due to browser caching)
* as a webpack-built `dist/` code with a JSON manifest (works for development,
  not ideal for production)
* as a webpack-built `dist/` code with manifest-as-PHP-file (not ideal for
  development, best for production)

The code will happily work with any configuration automatically and as a
developer working with the JS it's important to know the following logic:
1. If the `dist/manifest.php` file exists it will be used
2. If not, and the `dist/manifest.json` file exists, it will be used
3. If neither exist, the ES6 modules will be presented to the browser as-is

For JS developers, you either want to be working with the ES6 module code being
served directly to the browser (delete `dist/*`) or you want the `dist/*js` files
being auto-generated upon file change change (still delete `dist/manifest.php`).

webpack can watch the JS files and automatically rebuild `dist/*` with:
```bash
npx webpack --watch --progress
```

To generate the manifest files -- JSON or PHP -- directly, the flow is:
1. `npm run build` runs webpack and creates `dist/*` contents with JSON manifest
2. `php ./SETUP/generate_manifest_cache.php` generates a PHP file from the JSON
   manifest

### Browser caching & ES6 modules

The DP code assumes browsers are going to aggressively cache content like
JS and images. Indeed, the Apache config for pgdp.net explicitly tells the
browser to cache images, CSS, JS, and web fonts for a month to reduce the
amount of content served up to users. To make this work in practice, it falls
to the DP code to tell the browsers when that content has changed so the
browser will fetch the new content. This is particularly important for CSS
and JS.

The code does this by appending a query param to JS and CSS with the
modification time of the file (see `pinc/html_page_common.inc`). If the file
changes, the query param changes and browsers will fetch the new code.

For instance:
```html
<link type='text/css' rel='Stylesheet' href='https://www.pgdp.net/c/styles/statsbar.css?20250624204935'>
<script src='https://www.pgdp.org/c/scripts/api.js?20250624204935'></script>
```

This works great until we get to ES6 modules which allows JS files to reference
and include other JS files. The included JS files have static filenames without
query params (indeed, they are not supported) so there's no way to tell the
browser these files have changed without changing the filename itself.

This is where bundlers like webpack come in. The bundlers rename the files
to include a hash of the file contents and update all references to use the
new hashed filename. This forces web browsers to download the new file since
the filename has changed.

## Organizing Principles

The code is loosely organized around the following ideas:
* PHP pages end in `.php` extensions. As site entry points, all `.php` pages
  need to include `pinc/base.inc` which sets up common infrastructure like
  database connections, gettext for localization, error handlers, and more.
* PHP code that is included in PHP pages end in `.inc` extensions.
* Strings are localized by using gettext. These are wrapped in a `_()` function
  that the localization tooling will extract into `.po` files that volunteer
  translators will translate. We do not have any official change control over
  translated strings, just update them as necessary. See the
  [translation best practices](https://www.pgdp.net/wiki/DP_Code_Best_Practices#String_localization).

### Directories

* `pinc/` - Includes many of the `.inc` files -- this is short for *P*hp *INC*lude.
  * `pinc/3rdparty/` - code that we distribute but (generally) do not modify.
    See the [3rdparty readme](../pinc/3rdparty/README.md).
* `scripts/` - JS files used for various features.
* `styles/` - CSS scripts which include `.less` source files and the rendered
  `.css` files. See [CSS / style documentation](../styles/README.md).
* `SETUP/` - Non-runtime code, such as site admin docs, development docs, tests,
  tooling and other miscellanea. The expectation is that this directory
  will not, and should not be, accessible via the web context on a live site.
  * `SETUP/tests/` - Tests, mostly automated but some manual. See the
    [tests README](tests/README.md).
  * `SETUP/upgrade/` - Upgrade scripts. Each release gets a new subdir
    where we collect upgrade scripts for site admins to run when they install
    a new release. See also [UPGRADE](UPGRADE.md).
  * `SETUP/ci/` - CI scripts that are run as part of Github Actions -- and can
    be run manually. See `.github/workflows/ci.yml` for the GHA workflows.

## Development-related docs

* [coding standards](CODE_STYLE.md)
* [coding best practices](https://www.pgdp.net/wiki/DP_Code_Best_Practices)
* [automated tests](tests/README.md)
* [CSS / style documentation](../styles/README.md)
* [API documentation](../api/README.md)
* [coding documentation](CODE_DOCS.md)
* [graphs design](GRAPHS.md)
* [Unicode support](UNICODE.md)
