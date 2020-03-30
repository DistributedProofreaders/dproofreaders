# Code Style & Linting

## PHP

The PHP coding style is a bit all over the place after having numerous
developers add to the codebase over almost 20 years. A decade ago we
finally added some [Best Practices](https://www.pgdp.net/wiki/DP_Code_Best_Practices)
which provide guideance, but we do not currently have a code formatter
set up.

To do syntax-level linting of PHP files: `./lint_php_files.sh`

## Javascript

We use [eslint](https://eslint.org/) for basic JS code styling and linting.

To run the linter: `npm run lint`

To have eslint auto-fix linting issues where it can: `npm run lint-fix`

