# This configuration file is used for environment set up as part of
# Continuous Integration. Very (very) few of these variables are yet used for
# any part of the automated tests, but they need to be present to
# make sure the filled-in .template files are valid PHP files.

# This script exists simply to define variables for SETUP/configure, so
# disable unused variable warnings.
# shellcheck shell=bash disable=SC2034

_DB_SERVER=localhost
_DB_USER=dp_test_user
_DB_PASSWORD=dp_test_password
_DB_NAME=dp_test_db
_ARCHIVE_DB_NAME=dp_test_archive

_CODE_DIR=$PWD
_CODE_URL='http://127.0.0.1:12345'
_DYN_DIR=$PWD
_DYN_URL='http://127.0.0.1:12345'

_FORUM_TYPE=json
_JSON_USERS=$PWD/SETUP/tests/smoketests/users.json
_JSON_POSTS=$PWD/SETUP/tests/smoketests/posts.json

_DEFAULT_CHAR_SUITES='["basic-latin"]'

_SITE_NAME='CI'
_SITE_ABBREVIATION=TCI
_SITE_SIGNOFF="Ciao!"
_SITE_URL=$_CODE_URL

_SITE_REGISTRATION_PROTECTION_CODE=

_UPLOADS_DIR=~uploads
_UPLOADS_HOST=
_UPLOADS_ACCOUNT=dpscans
_UPLOADS_PASSWORD=PICK_A_PASSWORD

_ANTIVIRUS_EXECUTABLE=

_PROJECTS_DIR=$HOME/projects
_PROJECTS_URL='http://127.0.0.1:12345/projects'

_ARCHIVE_PROJECTS_DIR=$HOME/projects.archive

_API_ENABLED=true
_API_RATE_LIMIT=false
_API_RATE_LIMIT_REQUESTS_PER_WINDOW=3600
_API_RATE_LIMIT_SECONDS_IN_WINDOW=3600

_EXTERNAL_CATALOG_LOCATOR='z3950.loc.gov:7090/Voyager'

_PRECEDING_PROOFER_RESTRICTION=not_immediately_preceding

_PUBLIC_PAGE_DETAILS=false

_ASPELL_EXECUTABLE=/usr/bin/aspell
_ASPELL_PREFIX=/usr
_ASPELL_TEMP_DIR=/tmp

_PHPMAILER_SMTP_CONFIG='["Host" => "localhost", "Port" => 25]'
_NO_REPLY_EMAIL_ADDR=no-reply@pgdp.org
_GENERAL_HELP_EMAIL_ADDR=dphelp@pgdp.net
_DB_REQUESTS_EMAIL_ADDR=db-req@pgdp.org
_PPV_REPORTING_EMAIL_ADDR=ppv-reports@pgdp.org
_PROMOTION_REQUESTS_EMAIL_ADDR=dp-promote@pgdp.org
_IMAGE_SOURCES_EMAIL_ADDR=ism@pgdp.org
_TRANSLATION_COORDINATOR_EMAIL_ADDR=translation-coordinator@pgdp.org

_BLOG_URL=
_WIKI_URL=

_FORUMS_TEAMS_IDX=26
_FORUMS_PROJECT_WAITING_IDX=2
_FORUMS_PROJECT_AVAIL_IDX=3
_FORUMS_PROJECT_PP_IDX=4
_FORUMS_PROJECT_POSTED_IDX=5
_FORUMS_PROJECT_DELETED_IDX=48
_FORUMS_PROJECT_COMPLETED_IDX=48
_FORUMS_BEGIN_SITE_IDX=22
_FORUMS_POST_PROCESSORS_IDX=25

_AUTO_POST_TO_PROJECT_TOPIC=false

_USE_SECURE_COOKIES=true

_TESTING=true

_MAINTENANCE=false
_MAINTENANCE_MESSAGE=

_ORDINARY_USERS_CAN_SEE_QUEUE_SETTINGS=true

_GETTEXT_LOCALES_DIR=/usr/share/locale

TAG=master
GROUP=users
SHIFT_TO_LIVE=yes

# Hardcode the executables we know we're using in the CI environment
_PHP_CLI_EXECUTABLE=$(command -v php)
_XGETTEXT_EXECUTABLE=$(command -v xgettext)
_URL_DUMP_PROGRAM="$(command -v wget) --quiet --tries=1 --timeout=0 -O-"
