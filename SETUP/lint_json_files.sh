#!/bin/bash

if [ "_$1" != "_" ]; then
    BASE_DIR=$1
elif [ -d SETUP ]; then
    BASE_DIR=.
elif [ -d ../SETUP ]; then
    BASE_DIR=../
else
    echo "Unable to determine base code directory"
    exit 1
fi

echo "Checking all .json files under $BASE_DIR excluding node_modules for linting errors..."

for file in `find $BASE_DIR -name "*.json" -not -path "*/node_modules/*"`; do
    echo $file
    OUTPUT=$(php -r "exit(json_decode(file_get_contents('$file')) === NULL);" 2>&1)
    if [ $? -ne 0 -o "$OUTPUT" = '1' ]; then
        echo "ERROR: JSON lint failure in $file"
        exit 1
    fi
done
