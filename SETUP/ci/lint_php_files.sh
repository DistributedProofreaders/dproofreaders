#!/bin/bash
set -euo pipefail

if [ -n "${1-}" ]; then
    BASE_DIR=$1
elif [ -d SETUP ]; then
    BASE_DIR=.
elif [ -d ../../SETUP ]; then
    BASE_DIR=../../
else
    echo "Unable to determine base code directory"
    exit 1
fi

function lint_file()
{
    local file=$1
    echo -n .
    OUTPUT=$(php -l "$file" 2> /dev/null || true)
    if ! echo "$OUTPUT" | grep -q 'No syntax errors detected'; then
        echo 
        echo "ERROR: PHP lint failure in $file"
        echo "$OUTPUT"
        exit 1
    fi

    OUTPUT=$(file "$file")
    if ! echo "$OUTPUT" | grep -Eq 'ASCII|UTF-8'; then
        echo
        echo "ERROR: Unexpected encoding in $file"
        echo "$OUTPUT"
        exit 1
    fi
}

echo "Checking all .php and .inc files under $BASE_DIR for linting errors..."
N=$(nproc)
# shellcheck disable=SC2044
for file in $(find $BASE_DIR -name "*.php" -o -name "*.inc" | grep -v /vendor/ | grep -v /.phpstan.cache/); do
   lint_file "$file" &
   # Run at most N at a time.
   [[ $(jobs -r -p | wc -l) -ge $N ]] && wait -n
done

# Wait for all jobs to finish
wait

echo
echo "No PHP linting errors found."
