.PHONY: all less security_checks lint tests

SELF_DIR := $(dir $(lastword $(MAKEFILE_LIST)))

all: less security_checks lint

#----------------------------------------------------------------------------
# Security checks
security_checks:
	$(SELF_DIR)check_security.sh $(SELF_DIR)..
	$(SELF_DIR)check_require_login.php

#----------------------------------------------------------------------------
# File linting
lint:
	$(SELF_DIR)lint_php_files.sh $(SELF_DIR)..
	$(SELF_DIR)lint_json_files.sh $(SELF_DIR)..
	$(SELF_DIR)lint_charsuites.php $(SELF_DIR)..
	$(SELF_DIR)generate_css_from_less.sh --check $(SELF_DIR)../styles

#----------------------------------------------------------------------------
# CSS compilation

less:
	$(SELF_DIR)generate_css_from_less.sh $(SELF_DIR)../styles

#----------------------------------------------------------------------------
# Unit tests
tests:
	cd $(SELF_DIR)/tests && phpunit
