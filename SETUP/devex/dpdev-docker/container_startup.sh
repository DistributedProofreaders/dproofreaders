#!/bin/bash

# initialize the dyn_dir which is mounted as a volume, but check that it
# exists first just in case of a misconfiguration
DYN_DIR=/var/www/html/d
if [ -d $DYN_DIR ]; then
    mkdir -p $DYN_DIR/json_forum
    mkdir -p $DYN_DIR/locale
    mkdir -p $DYN_DIR/projects
    mkdir -p $DYN_DIR/uploads
    chown -R www-data:www-data $DYN_DIR
fi

# if there's a local script the user wants executed as part of
# container bring-up, run that too
LOCAL_STARTUP=/var/www/html/c/SETUP/devex/dpdev-docker/container_startup_local.sh
if [ -f $LOCAL_STARTUP ]; then
    echo "Local container startup script found, executing it"
    cd /var/www/html/c
    $LOCAL_STARTUP
fi

# end with the normal CMD used by the php:8.1-apache container
exec apache2-foreground
