#!/bin/bash

# Populate some DB tables used by the smoketests
mysql -uroot -proot < SETUP/tests/smoketests/test_tables.sql

# Create a project directory accessed by several pages
mkdir -p "${HOME}/projects/projectID5e23a810ef693"
for i in 001 002 003 004 005 illo; do
    cp SETUP/tests/smoketests/dot.png "${HOME}/projects/projectID5e23a810ef693/${i}.png"
done
mkdir -p "${HOME}/projects/projectID3141592653589"
