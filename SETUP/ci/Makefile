.PHONY: all less security_checks best_practice_checks static_checks code_style lint_charsuites lint_css lint_code lint less tests shellcheck

SELF_DIR := $(dir $(lastword $(MAKEFILE_LIST)))

all: lint code_style static_checks security_checks best_practice_checks

#----------------------------------------------------------------------------
# Security checks
security_checks:
	$(SELF_DIR)check_security.php
	$(SELF_DIR)check_require_login.php

#----------------------------------------------------------------------------
# Best Practice checks
best_practice_checks:
	$(SELF_DIR)check_includes.php

#----------------------------------------------------------------------------
# Static code checks
static_checks:
	cd $(SELF_DIR)../../ && ./vendor/bin/phpstan --memory-limit=512M

#----------------------------------------------------------------------------
# Code style checks
code_style:
	cd $(SELF_DIR)../../ && ./vendor/bin/php-cs-fixer check
	cd $(SELF_DIR)../../ && npm run format-check

#----------------------------------------------------------------------------
# File linting
lint_charsuites:
	$(SELF_DIR)lint_charsuites.php

lint_css:
	$(SELF_DIR)../generate_css_from_less.sh --check $(SELF_DIR)../../styles

lint_code:
	$(SELF_DIR)lint_php_files.sh
	$(SELF_DIR)lint_json_files.sh
	cd $(SELF_DIR)../../ && npm run lint

lint: lint_code lint_css lint_charsuites

#----------------------------------------------------------------------------
# CSS compilation

less:
	$(SELF_DIR)../generate_css_from_less.sh $(SELF_DIR)../../styles

#----------------------------------------------------------------------------
# Unit tests
tests:
	cd $(SELF_DIR)/../tests/unittests && ../../../vendor/bin/phpunit

#----------------------------------------------------------------------------
# Shellcheck: Shell script linter
shellcheck:
	find . -type f -name '*.sh' | xargs shellcheck
