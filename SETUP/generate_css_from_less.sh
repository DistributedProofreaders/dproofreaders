#!/bin/bash

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
NON_CSS_LESS_FILES=("theme.less")

function run_less {
    less_file=$1

    # skip files in NON_CSS_LESS_FILES that shouldn't generate a .css
    less_basename=$(basename -- "$less_file")
    if [[ " ${NON_CSS_LESS_FILES[@]} " =~ " ${less_basename} " ]]; then
        echo "Skipping $less_file"
        return;
    fi

    css_file="${less_file%.*}.css"
    echo "Generating $css_file"
    lessc $less_file $css_file
}

# run over every .less file in our style directory
for file in $(find $STYLE_DIR -name "*.less"); do
    run_less $file
done

# now the themes
