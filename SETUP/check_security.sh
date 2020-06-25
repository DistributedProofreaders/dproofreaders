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

echo "Checking PHP files for known security issues..."

for file in $(find $BASE_DIR -name "*.php" -o -name "*.inc"); do
    # skip files in SETUP
    echo $file | grep -q SETUP
    if [[ $? -eq 0 ]]; then
        continue;
    fi

    echo $file

    # die(mysqli_error(
    egrep -q 'die\s*\(\s*mysqli_error\s*\(' $file
    if [[ $? -eq 0 ]]; then
        echo "ERROR: found die() with mysqli_error output"
        exit 1
    fi

    # echo mysqli_error(
    egrep -q 'echo\s*mysqli_error\s*\(' $file
    if [[ $? -eq 0 ]]; then
        echo "ERROR: found echo with mysqli_error output"
        exit 1
    fi

    # mysqli_error(
    egrep -q 'mysqli_error\s*\(' $file
    if [[ $? -eq 0 ]]; then
        echo "WARNING: mysqli_error found - confirm it does not leak data to user"
    fi

done
