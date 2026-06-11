#!/bin/bash

if [ ! -f pinc/site_vars.php ]; then
    echo "$0: must be run from \$_CODE_DIR (checkout dir)"
    exit 1
fi

source SETUP/tests/ci_configuration.sh

# Populate some DB tables used by the smoketests
mysql -uroot -proot < SETUP/tests/smoketests/test_tables.sql

# Create wordcheck files
mkdir ${_DYN_DIR}/words
echo arid > ${_DYN_DIR}/words/bad_words.eng.txt
echo Sidenote > ${_DYN_DIR}/words/good_words.eng.txt

# Create a project directory accessed by several pages
mkdir -p "${_PROJECTS_DIR}/projectID5e23a810ef693"
for i in 001 002 003 004 005 illo; do
    cp SETUP/tests/smoketests/dot.png "${_PROJECTS_DIR}/projectID5e23a810ef693/${i}.png"
done
mkdir -p "${_PROJECTS_DIR}/projectID3141592653589"

