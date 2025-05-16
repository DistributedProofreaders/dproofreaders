#!/bin/bash

# TODO: these could be done as part of the docker entrypoint

mkdir -p /var/www/html/d/json_forum
mkdir -p /var/www/html/d/locale
mkdir -p /var/www/html/d/projects
mkdir -p /var/www/html/d/uploads
chown -R www-data:www-data /var/www/html/d
