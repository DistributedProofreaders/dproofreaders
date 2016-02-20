.PHONY: all less lint

SELF_DIR := $(dir $(lastword $(MAKEFILE_LIST)))
STYLE_DIR := $(SELF_DIR)../styles

LESSC := lessc
THEME_CSS := $(STYLE_DIR)/classic_grey.css $(STYLE_DIR)/project_gutenberg.css \
	$(STYLE_DIR)/royal_blues.css

all: less lint

less: $(STYLE_DIR)/*.css

$(THEME_CSS): $(STYLE_DIR)/theme.less

%.css: %.less $(STYLE_DIR)/global.less
	$(LESSC) $< $@

lint:
	$(SELF_DIR)lint_php_files.sh $(SELF_DIR)..
