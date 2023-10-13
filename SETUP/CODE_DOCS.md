# Documentation

Code-level docs can be generated using [phpDocumentor](https://www.phpdoc.org/).
A configuration file is included in the repo.

To generate the docs:
```bash
# at repo base
php path/to/phpDocumentor.phar run
```

This will generate documentation in `build/docs`.

Work is very slowly underway to update function comments to use DocBlocks
and until that work is completed the value of the documentation may be dubious.
