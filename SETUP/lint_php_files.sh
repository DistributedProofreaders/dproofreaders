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

echo "Checking all .php and .inc files under $BASE_DIR for linting errors..."

for file in $(find $BASE_DIR -name "*.php" -o -name "*.inc" | grep -v vendor); do
    echo $file
    OUTPUT=`php -l $file`
    echo $OUTPUT | grep -q 'No syntax errors detected'
    if [ $? -ne 0 ]; then
        echo "ERROR: PHP lint failure in $file"
        echo "$OUTPUT"
        exit 1
    fi

    OUTPUT=$(file $file)
    echo $OUTPUT | grep -q 'ASCII'
    ASCII=$?
    echo $OUTPUT | grep -q 'UTF-8'
    UTF8=$?
    if [ $ASCII -eq 1 -a $UTF8 -eq 1 ]; then
        echo "ERROR: Unexpected encoding in $file"
        echo "$OUTPUT"
        exit 1
    fi
done
