# Make an editable copy of this file, put it *outside* your
# server's doc root, and edit that file to configure your DP system.
# ----------------------------------------------------------------------

# Set this file's variables to values that are appropriate for your
# system. In some cases, the setting that already appears here may
# be satisfactory. However, you will definitely need to change any value
# that refers to 'example.org', and any value that's a password.

# This file (or rather, your edited copy of it) is sourced by the scripts
# 'update_from_cvs' and 'configure'.  Those are Bourne Shell scripts,
# so you can use any syntax that /bin/sh allows.  However, in typical
# usage, you would merely assign literal values to shell variables.

# Security:
#
# Because this file will contain various passwords, you should be
# careful not to allow it to be seen by unprivileged users. In
# particular, don't put it (or leave it) under your server's doc root.

# ----------------------------------------------------------------------

# $SHIFT_TO_LIVE should be 'yes', 'no', or 'prompt'.
# If it's 'yes', or it's 'prompt' and the user answers 'y',
# then $_CODE_DIR.new will be moved to $_CODE_DIR.
# If that directory already exists, it will first be renamed as
#     $_CODE_DIR.bak
# and if *that* directory already exists, it will be REMOVED.
SHIFT_TO_LIVE=prompt

# $GROUP is the name of the group that will group-own the files.
GROUP=$USER

# ----------------------------------------------------------------------
# We don't mandate the relative locations of c, d, projects, etc.,
# so you can set the following variables however you like. However,
# we've found it convenient to make them siblings (children of a
# base dir/url), so the following code is slanted in that direction:
# if you choose to use this layout, then you only need to set
# $base_dir and $base_url, and the other variables in this section
# will be set accordingly.

base_dir=/home/$USER/public_html
base_url=http://www.example.org/~$USER

_CODE_DIR=$base_dir/c
_CODE_URL=$base_url/c

_PROJECTS_DIR=$base_dir/projects
_PROJECTS_URL=$base_url/projects

_DYN_DIR=$base_dir/d
_DYN_URL=$base_url/d

_JPGRAPH_DIR=$base_dir/jpgraph

_FORUMS_DIR=$base_dir/phpBB2
_FORUMS_URL=$base_url/phpBB2

# ----------------------------------------------------------------------

_DB_SERVER=localhost
_DB_NAME=PICK_A_DB_NAME
_DB_USER=PICK_A_USER_NAME
_DB_PASSWORD=PICK_A_HARD_PASSWORD

_UPLOADS_DIR=/home/dpscans
_UPLOADS_HOST=www.example.org
_UPLOADS_ACCOUNT=dpscans
_UPLOADS_PASSWORD=PICK_A_PASSWORD

_ASPELL_EXECUTABLE=/usr/local/bin/aspell
_ASPELL_PREFIX=/usr/local
_ASPELL_TEMP_DIR=/tmp/sp_check

_NO_REPLY_EMAIL_ADDR=no-reply@example.org
_GENERAL_HELP_EMAIL_ADDR=dphelp@example.org

_USE_PHP_SESSIONS=TRUE
_COOKIE_ENCRYPTION_KEY=A_LONG_STRING_OF_GIBBERISH

_TESTING=TRUE
_MAINTENANCE=0
_METADATA=FALSE
_CHARSET='ISO-8859-1'
_WRITEBIGTABLE=TRUE
_READBIGTABLE=FALSE

# ----------------------------------------------------------------------
