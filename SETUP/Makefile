.PHONY: all lint tests

SELF_DIR := $(dir $(lastword $(MAKEFILE_LIST)))

all: lint

lint:
	$(SELF_DIR)lint_php_files.sh $(SELF_DIR)..

tests:
	cd $(SELF_DIR)/tests && phpunit --bootstrap phpunit_bootstrap.php --verbose .
