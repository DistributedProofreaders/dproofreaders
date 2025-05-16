#!/bin/bash

# This script pre-populates the database and the JSON-based "forum" with
# some existing users and project data. It downloads the project files
# from the pgdp.org TEST server so this requires internet access.

DIR=$(dirname $0)

# create the users
php $DIR/init_users.php

# load the database
cat $DIR/dp_db.sql | mysql -h mysql -udp_user -pdp_password dp_db

# pull down the zip file into the download directory
mkdir -p /var/www/html/d/uploads/Users/pm/
curl -o /var/www/html/d/uploads/Users/pm/demonology_pages.zip \
    https://www.pgdp.org/dp-devel-VM/demonology_pages.zip

# populate the image files for the loaded project from the zip
mkdir -p /var/www/html/d/projects/projectID5682a1735f76a/
unzip -q -j -d /var/www/html/d/projects/projectID5682a1735f76a/ \
    /var/www/html/d/uploads/Users/pm/demonology_pages.zip
rm /var/www/html/d/projects/projectID5682a1735f76a/*txt

chown -R www-data:www-data /var/www/html/d
