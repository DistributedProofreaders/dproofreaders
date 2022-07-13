#!/bin/bash

if [ "$1" = "--check" ]; then
    CHECK=1
    shift
else
    CHECK=0

    # exit on errors
    set -e
fi

if [ "_$1" != "_" ]; then
    STYLE_DIR=$1
elif [ -d styles ]; then
    STYLE_DIR=styles
elif [ -d ../styles ]; then
    STYLE_DIR=../styles
else
    echo "Unable to determine styles directory"
    exit 1
fi

# Array of .less files that should not turn into a .css file
NON_CSS_LESS_FILES=("layout.less" "global.less" "page_interfaces.less")

function run_less {
    less_file=$1

    # skip files in NON_CSS_LESS_FILES that shouldn't generate a .css
    less_basename=$(basename -- "$less_file")
    if [[ " ${NON_CSS_LESS_FILES[@]} " =~ " ${less_basename} " ]]; then
        echo "Skipping $less_file"
        return;
    fi

    css_file="${less_file%.*}.css"
    if [ $CHECK -eq 0 ]; then
        echo "Generating $css_file"
        ../node_modules/.bin/lessc $less_file $css_file
    else
        echo "Validating $css_file"
        new_css_file="${css_file}.new"
        ../node_modules/.bin/lessc $less_file $new_css_file
        diff -q $css_file $new_css_file > /dev/null 2>&1
        if [ $? -ne 0 ]; then
            echo "ERROR: $css_file doesn't match generated less file"
            rm $new_css_file
            exit 1
        fi
        rm $new_css_file
    fi
}

# run over every .less file in our style directory
for file in $(find $STYLE_DIR -name "*.less"); do
    run_less $file
done

# now the themes
