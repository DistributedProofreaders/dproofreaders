#!/bin/bash

for po_file in `find . -name '*.po'`; do
    mo_file=`echo $po_file | sed 's/.po/.mo/'`
    echo Compiling $po_file to $mo_file
    msgfmt $po_file -o $mo_file
done
