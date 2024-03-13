#!/bin/bash
set -euo pipefail
# shellcheck disable=SC2044
for po_file in $(find . -name '*.po'); do
    mo_file="${po_file%.po}.mo"
    echo "Compiling $po_file to $mo_file"
    msgfmt "$po_file" -o "$mo_file"
done
