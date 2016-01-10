.PHONY: all lint

SELF_DIR := $(dir $(lastword $(MAKEFILE_LIST)))

all: lint

lint:
	$(SELF_DIR)lint_php_files.sh $(SELF_DIR)..
