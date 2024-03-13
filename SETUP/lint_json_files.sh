#!/bin/bash
set -euo pipefail

if [ -n "${1-}" ]; then
    BASE_DIR=$1
elif [ -d SETUP ]; then
    BASE_DIR=.
elif [ -d ../SETUP ]; then
    BASE_DIR=../
else
    echo "Unable to determine base code directory"
    exit 1
fi

echo "Checking all .json files under $BASE_DIR excluding node_modules/ and vendor/ for linting errors..."

function lint_json()
{
    local file=$1
    echo "$file"
    if ! OUT=$(php -r "exit(json_decode(file_get_contents('$file')) === NULL);" 2>&1) || [ "$OUT" = 1 ]; then
        echo "ERROR: JSON lint failure in $file"
        exit 1
    fi
}

N=$(nproc)
# shellcheck disable=SC2044
for file in $(find $BASE_DIR -name "*.json" -not -path "*/node_modules/*" -not -path "*/vendor/*"); do
    lint_json "$file" &
    # Run at most N at a time.
    [[ $(jobs -r -p | wc -l) -ge $N ]] && wait -n
done

# Wait for all jobs to finish
wait
