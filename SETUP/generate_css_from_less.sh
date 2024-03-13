#!/bin/bash
set -euo pipefail

CHECK=0
if [ "${1-}" = "--check" ]; then
    CHECK=1
    shift
fi

if [ -n "${1-}" ]; then
    STYLE_DIR=$1
elif [ -d styles ]; then
    STYLE_DIR=styles
elif [ -d ../styles ]; then
    STYLE_DIR=../styles
else
    echo "Unable to determine styles directory"
    exit 1
fi

LESSC=$STYLE_DIR/../node_modules/.bin/lessc

# Array of .less files that should not turn into a .css file
NON_CSS_LESS_FILES=(layout.less global.less page_interfaces.less)

function run_less {
    less_file=$1

    # skip files in NON_CSS_LESS_FILES that shouldn't generate a .css
    less_basename=$(basename -- "$less_file")
    for skip_file in "${NON_CSS_LESS_FILES[@]}"; do
        [[ "$less_basename" = "$skip_file" ]] && return;
    done

    css_file="${less_file%.*}.css"
    if [ $CHECK -eq 0 ]; then
        echo "Generating $css_file"
        $LESSC "$less_file" "$css_file"
    else
        echo "Validating $css_file"
        if ! diff -q "$css_file" <($LESSC "$less_file") > /dev/null 2>&1; then
            echo "ERROR: $css_file doesn't match generated less file"
            exit 1
        fi
    fi
}

# run over every .less file in our style directory
# shellcheck disable=SC2044
for file in $(find $STYLE_DIR -name "*.less"); do
    run_less "$file"
done

# now the themes
