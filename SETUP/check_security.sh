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

echo "Checking PHP files for known security issues..."

for file in $(find $BASE_DIR -name "*.php" -o -name "*.inc" | grep -v vendor); do
    # skip files in SETUP
    [[ "$file" =~ SETUP ]] && continue

    echo "$file"

    # die(mysqli_error(
    if grep -Eq 'die\s*\(\s*mysqli_error\s*\(' "$file"; then
        echo "ERROR: found die() with mysqli_error output"
        exit 1
    fi

    # echo mysqli_error(
    if grep -Eq 'echo\s*mysqli_error\s*\(' "$file"; then
        echo "ERROR: found echo with mysqli_error output"
        exit 1
    fi

    # mysqli_error(
    if grep -Eq 'mysqli_error\s*\(' "$file"; then
        echo "WARNING: mysqli_error found - confirm it does not leak data to user"
    fi

done
