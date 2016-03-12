.PHONY: all less lint

SELF_DIR := $(dir $(lastword $(MAKEFILE_LIST)))

all: less lint

#----------------------------------------------------------------------------
# PHP file linting
lint:
	$(SELF_DIR)lint_php_files.sh $(SELF_DIR)..

#----------------------------------------------------------------------------
# CSS compilation
LESSC := lessc
STYLE_DIR := $(SELF_DIR)../styles
BASE_LESS := $(wildcard $(STYLE_DIR)/*.less)
BASE_CSS := $(BASE_LESS:.less=.css)
THEME_BASE := $(STYLE_DIR)/themes/theme.less
# theme.less is a dependency, but should't be compiled itself
THEME_LESS := $(filter-out $(THEME_BASE),$(wildcard $(STYLE_DIR)/themes/*.less))
THEME_CSS := $(THEME_LESS:.less=.css)
CSS := $(BASE_CSS) $(THEME_CSS)

less: $(CSS)

# All CSS files depend on global.less
$(BASE_CSS): $(STYLE_DIR)/global.less

# All theme files depend on the theme base and global.less
$(THEME_CSS): $(THEME_BASE) $(STYLE_DIR)/global.less

$(CSS):
	$(LESSC) $(@:.css=.less) $@
