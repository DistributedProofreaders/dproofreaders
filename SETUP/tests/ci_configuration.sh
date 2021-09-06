# This configuration file is used for environment set up as part of
# Continuous Integration. Very (very) few of these variables are yet used for
# any part of the automated tests, but they need to be present to
# make sure the filled-in .template files are valid PHP files.

_DB_SERVER=localhost
_DB_USER=dp_user
_DB_PASSWORD=dp_password
_DB_NAME=dp_db
_ARCHIVE_DB_NAME=dp_archive

_CODE_DIR=$HOME
_CODE_URL='http://localhost'
_DYN_DIR=$HOME
_DYN_URL='http://localhost'

_PHPBB_VERSION=3
_PHPBB_TABLE_PREFIX=phpbb3.phpbb
_FORUMS_DIR=$HOME/phpBB3
_FORUMS_URL='http://localhost/phpBB3'

_DEFAULT_CHAR_SUITES='[ "basic-latin" ]'

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
_PROJECTS_URL='http://localhost/projects'

_ARCHIVE_PROJECTS_DIR=$HOME/projects.archive

_API_ENABLED=FALSE
_API_RATE_LIMIT=FALSE
_API_RATE_LIMIT_REQUESTS_PER_WINDOW=3600
_API_RATE_LIMIT_SECONDS_IN_WINDOW=3600

_EXTERNAL_CATALOG_LOCATOR='z3950.loc.gov:7090/Voyager'

_PRECEDING_PROOFER_RESTRICTION=not_immediately_preceding

_PUBLIC_PAGE_DETAILS=FALSE

_ASPELL_EXECUTABLE=/usr/bin/aspell
_ASPELL_PREFIX=/usr
_ASPELL_TEMP_DIR=/tmp

_WIKIHIERO_DIR=
_WIKIHIERO_URL=

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
_FORUMS_GENERAL_IDX=21
_FORUMS_BEGIN_SITE_IDX=22
_FORUMS_BEGIN_PROOF_IDX=23
_FORUMS_CONTENT_PROVIDERS_IDX=24
_FORUMS_POST_PROCESSORS_IDX=25

_AUTO_POST_TO_PROJECT_TOPIC=FALSE

_USE_PHP_SESSIONS=TRUE
_USE_SECURE_COOKIES=TRUE
_COOKIE_ENCRYPTION_KEY=

_TESTING=TRUE

_MAINTENANCE=FALSE
_MAINTENANCE_MESSAGE=

_METADATA=FALSE
_CORRECTIONS=FALSE

_ORDINARY_USERS_CAN_SEE_QUEUE_SETTINGS=TRUE

_GETTEXT_LOCALES_DIR=/usr/share/locale

TAG=master
GROUP=users
SHIFT_TO_LIVE=yes

_PHP_CLI_EXECUTABLE=`which php`
_XGETTEXT_EXECUTABLE=`which xgettext`

_URL_DUMP_PROGRAM=
if [ "$_URL_DUMP_PROGRAM" == "" ]; then
    # No program explicitly specified, attempt to find: wget, curl, lynx
    program_test=`which wget`
    if [ $? -eq 0 ]; then
        _URL_DUMP_PROGRAM="$program_test --quiet --tries=1 --timeout=0 -O-"
    else
        program_test=`which curl`
        if [ $? -eq 0 ]; then
            _URL_DUMP_PROGRAM="$program_test --silent"
        else
            program_test=`which lynx`
            if [ $? -eq 0 ]; then
                _URL_DUMP_PROGRAM="$program_test -source"
            else
                echo "ERROR: No program found to dump URLs."
                echo "       Edit the configuration file and set _URL_DUMP_PROGRAM."
                exit 1
            fi
        fi
    fi
fi
