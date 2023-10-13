# Code Style & Linting

See [CODE_DOCS.md](CODE_DOCS.md) for information on code documentation.

## PHP

We use [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) for PHP
code formatting and include a `.php_cs.dist` with some small deviations from
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
./vendor/bin/php-cs-fixer fix --config=.php_cs.dist file1 file2 ...
```

See also our [Best Practices](https://www.pgdp.net/wiki/DP_Code_Best_Practices)
guide in the wiki.

## Javascript

We use [eslint](https://eslint.org/) for basic JS code styling and linting.

To run the linter: `npm run lint`

To have eslint auto-fix linting issues where it can: `npm run lint-fix`

