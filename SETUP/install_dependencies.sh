#!/bin/bash

# Stop on undefined variables or errors
set -u
set -e

PROD=0
DIR=$(pwd)
SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

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
    echo "Installing the dev dependencies for building artifacts..."
    npm ci

    echo "Building the dist/* artifacts..."
    npm run build

    # create the manifest cache
    $SCRIPT_DIR/generate_manifest_cache.php $DIR

    echo "Installing only the production dependencies..."
    npm ci --omit dev --omit optional

    # We only want node_modules to be pulling in JS and CSS assets, but
    # there's a chance they might have .php scripts in them too which we
    # don't want, so find and delete them if any exist.
    find node_modules -name '*.php' -delete
else
    npm install

    if [ -f "$DIR/dist/manifest.php" ]; then
        echo "Deleting cached manifest"
        rm "$DIR/dist/manifest.php"
    fi
fi

echo "--------------------------------------------"
if [ $PROD -eq 1 ]; then
    echo "Production dependencies installed"
else
    echo "Development dependencies installed"
fi
