# Code Style & Linting

See [CODE_DOCS.md](CODE_DOCS.md) for information on code documentation.

## PHP

We use [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) for PHP
code formatting and include a `.php-cs-fixer.dist.php` with some small deviations from
[PSR-2](https://www.php-fig.org/psr/psr-2/).

PHP-CS-Fixer is included as a composer development package and can be installed
with:
```bash
composer install --dev
```

To run it across the codebase and fix any formatting issues, run:
```bash
./vendor/bin/php-cs-fixer fix
```

To run it against just a specific set of files:
```bash
./vendor/bin/php-cs-fixer fix file1 file2 ...
```

See also our [Best Practices](https://www.pgdp.net/wiki/DP_Code_Best_Practices)
guide in the wiki.

## Javascript

We use [prettier](https://prettier.io/) for JS
code formatting.

prettier is included as an npm development dependency and can be installed
with:
```bash
npm install
```

Npm can be installed with node as documented in [DEVELOPMENT](DEVELOPMENT.md).

To run the linter:
```bash
npm run format-check
```

To have prettier auto-fix formatting issues where it can run:
```bash
npm run format
```

