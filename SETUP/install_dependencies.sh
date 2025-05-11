#!/bin/bash

# Stop on undefined variables or errors
set -u
set -e

PROD=0
DIR=$(pwd)

usage() {
    echo "Usage: $0 [-p] [-d <dir>]" 1>&2;
    exit 1;
}

while getopts "d:p" o; do
    case "${o}" in
        d)
            DIR=${OPTARG}
            ;;
        p)
            PROD=1
            ;;
        *)
            usage
            ;;
    esac
done
shift $((OPTIND-1))

if [ $PROD -eq 1 ]; then
    echo "Installing production dependencies"
else
    echo "Installing development dependencies"
fi

cd $DIR

# ------------------------------------------------------------------------------
# Install PHP dependencies via composer

echo
echo "Installing PHP dependencies..."

if [ ! -f "composer.json" ]; then
    echo "ERROR: No composer dependencies found in $DIR (missing composer.json)"
    exit 1
fi

if [ $PROD -eq 1 ]; then
    composer install --no-dev
    echo "Require all denied" > vendor/.htaccess
else
    composer install
fi

# ------------------------------------------------------------------------------
# Install JS dependencies via npm

echo
echo "Installing JS dependencies..."

if [ ! -f "package-lock.json" ]; then
    echo "ERROR: No npm dependencies found in $DIR (missing package-lock.json)"
    exit 1
fi

if [ $PROD -eq 1 ]; then
    npm ci --omit dev --omit optional
else
    npm install
fi
