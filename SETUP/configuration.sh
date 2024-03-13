# DP site configuration script
# ======================================================================

# This script is used to configure the DP site code by defining shell
# variables used to populate some template files.
#
# Make an editable copy of this file, put it *outside* your web
# server's doc root, and edit that file to configure your DP system.

# Set this file's variables to values that are appropriate for your
# system. In some cases, the setting that already appears here may
# be satisfactory. However, you will definitely need to change any value
# that refers to 'example.org', and any value that's a password.

# SECURITY WARNING:
# Because this file will contain various passwords, you should be
# careful not to allow it to be seen by unprivileged users. In
# particular, don't put it (or leave it) under your server's doc root.

# This file (or rather, your edited copy of it) is sourced by the scripts
# 'update_from_github' and 'configure'.  Those are Bourne Shell scripts,
# so you can use any syntax that /bin/bash allows.  However, in typical
# usage, you would merely assign literal values to shell variables.

# Bug/limitation: if a variables value contains an apostrophe, the
# configuration process will not work correctly. (Note that it's fine to
# use apostrophes to delimit string literals; they aren't part of the
# value.) This is unlikely to be a problem outside the "Identifying
# the Site" section.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# This script exists simply to define variables for SETUP/configure, so
# disable unused variable warnings.
# shellcheck shell=bash disable=SC2034

# Database access
# ---------------

# These parameters specify database connection and configuration settings.
# See SETUP/INSTALL.md for instructions on how to create the database
# and user.

_DB_SERVER=localhost
_DB_USER=PICK_A_USER_NAME
_DB_PASSWORD=PICK_A_HARD_PASSWORD
_DB_NAME=PICK_A_DB_NAME

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# File and URL locations
# -----------------------------

# We don't require a particular arrangement of directories configured in
# this file, but we've found it convenient to make most of them siblings
# (children of a base directory), so the following code is slanted in that
# direction: if you choose to use this layout, then you only need to set
# $base_dir and $base_url, and most of the _DIR/_URL variables
# will be set accordingly.
base_dir=/home/$USER/public_html
base_url=http://www.example.org/~$USER

# For a variable whose name ends in _DIR, the value should be an absolute path
# to a directory in the local file system.
# For a variable whose name ends in _URL, the value should be an absolute URL.
# For a DIR/URL pair, the URL should resolve to the DIR.

# ----------------------------------------------------------------------

# Code location and URL access
# ----------------------------

_CODE_DIR=$base_dir/c
_CODE_URL=$base_url/c
# The location where the code was installed.
# (It corresponds to the root directory in the git repository
# and should contain directories such as 'pinc' and 'tools'.)

_DYN_DIR=$base_dir/d
_DYN_URL=$base_url/d
# This directory houses two classes of DP-related files that must be
# readable and writable by the web server.
#
# Optional user-supplied files that are only read by the code:
#
# --- $_DYN_DIR/code_images/page_header/$stage_id.<ext>
#     $_DYN_DIR/code_images/page_header/<locale>/$stage_id.<ext>
#         Images to show on stage pages. If the images don't exist nothing
#         is shown. Images with text can be localized by placing them in
#         locale-specific subdirectories. If a locale-free version is
#         found, that version is used before a locale-specific version.
#         The images can be of type png, jpg, or gif and are selected in
#         that order using those extensions.
#
#
# Files created and managed by the code:
#
# --- $_DYN_DIR/download_tmp/*_images.zip
#         Image zips, temporarily available for download.
#
# --- $_DYN_DIR/locale/
#         For the purposes pf 'gettext', this directory is bound to the
#         'messages' domain.
#
# --- $_DYN_DIR/pg/catalog.rdf
#         The current PG catalog, obtained from the PG site.
#
# --- $_DYN_DIR/stats/automodify_logs/*.txt
#         Logs of runs of automodify.php
#
# --- $_DYN_DIR/teams/avatar/*
# --- $_DYN_DIR/teams/icon/*
#         Avatar and icon images for teams.
#
# --- $_DYN_DIR/xmlfeeds/*.xml
#         News feeds for the site.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Forum configuration
# -------------------------

# The DP code uses a forum for authentication and facilitating communication
# about projects. The following types are supported:
#   phpbb3 -- phpBB versions 3.0, 3.1, 3.2, and 3.3
#   json -- a _test only_ forum used for development
_FORUM_TYPE=phpbb3

# phpBB3 forum configuration options
#
# Upon installation, phpBB tables are prefixed with a specific string.
# The DP code needs to know what this prefix is in order to access the
# tables directly.
#
# If you have installed the phpBB tables in their own database, include
# the database name and the prefix here. For example, if the phpBB
# were using the 'phpbb3db' database and a prefix of 'phpbb', set this
# value to phpbb3db.phpbb
_PHPBB_TABLE_PREFIX=phpbb
#
# Locations of the phpBB code via filesystem path and URL.
# These should not contain a trailing /
_PHPBB_DIR=$base_dir/phpBB3
_PHPBB_URL=$base_url/phpBB3

# JSON forum configuration options
#
# Location of JSON files, must be writable by web server
_JSON_USERS=
_JSON_POSTS=

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Character suites
# ----------------

# To help provide better quality UTF-8 texts, the code requires project
# managers to select which characters are valid for a project. These
# characters are defined in a Character Suite. If you want all new
# projects to have specific character suites selected upon creation,
# specify them here using PHP array syntax within a string. An empty
# array can be used to disable default character suites. All character
# suites must be installed and enabled on the system.
# See SETUP/UNICODE.md for more information.

_DEFAULT_CHAR_SUITES='[ "basic-latin" ]'

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Identifying the Site
# --------------------

# (Bug/limitation: The values for the next three variables should avoid
# three characters that are special to HTML/XML: < > &.)

# Something like 'Distributed Proofreaders' would be good. It should
# make sense in contexts like 'Welcome to %s', and 'the %s website'.
_SITE_NAME=PICK_A_NAME

# Something like 'DP' would be good. It will be used in the HTML title
# of each page ('%s: Welcome') and in the subject line of emails.
_SITE_ABBREVIATION=PICK_AN_ABBREVIATION

# You can think of this as the "publishable" HTTP URL for the site.
# So far, it's used in email footers and project credits.
# -- It could be exactly the same as $_CODE_URL.
# -- Or it might be a more memorable or more permanent URL that simply
#    redirects to $_CODE_URL.
# -- Or it might be the address of some site-specific content, perhaps
#    a pre-introduction, which would presumably include a link to $_CODE_URL.
# (DP-US uses the second option, because PG didn't want us to use the first.)
_SITE_URL=$_CODE_URL

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# User registration
# -----------------

# User registration is done in two steps:
#   1) User fills out registration form; code sends activation email
#   2) User clicks activation URL; code adds account via forum software
#
# To help prevent bot registrations, you can create site-specific
# code for use in step 1 (such as blocking specific IPs or domains from
# registering, implementing CAPTCHs, etc).
#
# To do so, copy SETUP/site_registration_protection.php.example somewhere
# accessible to the web server, remove .example extension, implement the
# features you want, and update _SITE_REGISTRATION_PROTECTION_CODE to point
# to the absolute path of the file.
_SITE_REGISTRATION_PROTECTION_CODE=

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Uploading and Creating Projects
# -------------------------------

# To load images and text into projects, these files must reside on the
# web server. There are two common ways to accomplish this:
#   1) Via the built-in project upload script (recommended)
#   2) Via an FTP server that you configure and manage separately
#
# Previously at pgdp.net, Project Managers were given access to a shared
# ftp-only "uploads" account. Each PM created a personal directory (named
# according to their DP login id) within the shared account's home
# directory. This has since been replaced with the remote_file_manager.php
# script.

# The location where project information (text and images) are written
# to by remote_file_manager.php and read from for loading into projects.
# The web server must have read/write permissions on this directory.
# If using FTP, this should be the root directory for the
# _UPLOADS_ACCOUNT user.
_UPLOADS_DIR=/home/dpscans

# If using FTP, set the following parameters to have them echoed to
# users as reminders. If _UPLOADS_HOST is blank, these will not be
# displayed to users.
_UPLOADS_HOST=www.example.org
_UPLOADS_ACCOUNT=dpscans
_UPLOADS_PASSWORD=PICK_A_PASSWORD

# remote_file_manager.php will scan uploaded files for viruses
# if an antivirus scanner, like clamscan, is installed. Any scanner
# will work as long as it accepts the following syntax
#   av_executable -- filename
# and returns 0 on pass and non-zero on failure.
_ANTIVIRUS_EXECUTABLE=

# ----------------------------------------------------------------------

_PROJECTS_DIR=$base_dir/projects
_PROJECTS_URL=$base_url/projects

# When you "Create a Project", the code creates a directory for that
# project as a sub-directory of $_PROJECTS_DIR.
#
# When you "Add text and images" to that project (from a specified
# directory under $_UPLOADS_DIR), the code looks at the data, copies
# image files into the project directory, and loads text data into the
# database.
#
# The project directory also holds other files relating to the project.

# ----------------------------------------------------------------------

_EXTERNAL_CATALOG_LOCATOR='lx2.loc.gov:210/LCDB'

# When you Create a Project, you have the option of searching an
# external (Z39.50-enabled) catalog for bibliographic information.
# This variable specifies the catalog to search.

# It should be a locator in the form 'host[:port][/database]', suitable
# for passing to 'yaz_connect'.

# To disable external search at project-creation time, leave this
# variable empty.

# See http://www.loc.gov/z3950/lcserver.html for US Library of Congress
# server addresses and URLs.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Proofreading Projects
# ---------------------

_PRECEDING_PROOFER_RESTRICTION=not_immediately_preceding

# When a user asks to proofread a page in a project, the code chooses
# which page to assign to the user. Generally, it just chooses the "next"
# (i.e., lowest-numbered) available page, but it can be configured (with
# this setting) to avoid pages that the user has worked on before.
# Specifically, there are three recognized values for this setting:
# --- empty string: Anyone can work on any page in any round,
#     regardless of which pages they've done in previous rounds.
# --- 'not_immediately_preceding': All pages are available, except those
#     completed by the current user in the round immediately preceding
#     the current round (where the definition of "immediately preceding"
#     takes skipped rounds into account).
# --- 'not_previously_proofed': All pages are available, except those
#     which have been completed by the current user in any prior round.

# ----------------------------------------------------------------------

_PUBLIC_PAGE_DETAILS=false

# When a user proofs a page, the site records their username, and can
# display this in various places, including the page details table and
# concatenated text files.  This setting controls the visibility of
# those names.
# If `true`, all logged-in users can see all names for all pages in all
#     projects.
# If `false`, the proofreader names for a given page in a given project
#     are visible only to:
#     --- Site Administrators and Project Facilitators;
#     --- the project's PM, PPer, and PPVer; and
#     --- any other user that worked on the same page.

# ----------------------------------------------------------------------

# In the proofreading interface, the WordCheck functionality relies on
# a local installation of 'aspell'...

_ASPELL_EXECUTABLE=/usr/local/bin/aspell
# The location of the aspell executable.

_ASPELL_PREFIX=/usr/local
# The root of all aspell dir ./bin/ etc.
# (passed to aspell as --prefix=$_ASPELL_PREFIX)

_ASPELL_TEMP_DIR=/tmp/sp_check
# The document root for aspell temp files.
# We recommend putting it in its own dir for easy purging,
# and to help keep /tmp itself uncluttered.

# ----------------------------------------------------------------------

_WIKIHIERO_DIR=
_WIKIHIERO_URL=

# If you're dealing with text containing Egyptian hieroglyphs, you may
# want to install wikihiero to help with the transcription. If so, set
# these variables to the location you installed it, and a link will
# appear in the proofreading interface. See INSTALL.md for more info.
# If you haven't installed wikihiero, leave them empty.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Archiving Projects
# ------------------

# After projects are completed, they are eventually archived to a separate
# database and project directory. See SETUP/ARCHIVING.md for how to set up
# project archiving.

_ARCHIVE_DB_NAME=

# Archive database name.

_ARCHIVE_PROJECTS_DIR=$base_dir/archive

# Where the project files are archived to.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# API
# ---

# The API allows programmatic access to users who have been configured
# with an API key or to UI users with a valid session.
# See API.md for more information on how to set up and use the API.

# Note: Some UI features, such as the Page Browser, require the API.
#       Disabling it will break these features.

_API_ENABLED=true
_API_RATE_LIMIT=false
_API_RATE_LIMIT_REQUESTS_PER_WINDOW=3600
_API_RATE_LIMIT_SECONDS_IN_WINDOW=3600

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Communication/Discussion
# ------------------------

# SMTP configuration for PHPMailer. Keys are attribute names to the PHPMailer
# object and the values are their setting
_PHPMAILER_SMTP_CONFIG='["Host" => "localhost", "Port" => 25]'

_NO_REPLY_EMAIL_ADDR=no-reply@example.org
# Email address to use on outgoing emails when no response is expected.

_GENERAL_HELP_EMAIL_ADDR=dphelp@example.org
_DB_REQUESTS_EMAIL_ADDR=db-requests@example.org
_PPV_REPORTING_EMAIL_ADDR=ppv-reports@example.org
_PROMOTION_REQUESTS_EMAIL_ADDR=dp-promote@example.org
_IMAGE_SOURCES_EMAIL_ADDR=ism@example.org
_TRANSLATION_COORDINATOR_EMAIL_ADDR=translation-coord@example.org
# These addresses are used in various places for users to request help.
# These can be set to any working email address.

_BLOG_URL=
_WIKI_URL=$base_url/wiki
# If there's a wiki or a blog that you want your users to use, set the
# above variables to their URLs and links will appear on the navigation
# bar. If you don't have a blog or wiki, set either or both of them to
# the empty string, and they won't appear in the navigation bar.

_FORUMS_TEAMS_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
# Each team gets an automatically-created discussion topic in this forum

_FORUMS_PROJECT_WAITING_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_PROJECT_AVAIL_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_PROJECT_PP_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_PROJECT_POSTED_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
# Each project gets an automatically-created discussion topic, which
# moves around among these 4 forums, depending on its state.
# (The exact correspondence is defined in pinc/project_states.inc.)

_FORUMS_PROJECT_DELETED_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_PROJECT_COMPLETED_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
# Projects that are deleted or completed and not posted (rare)
# have a forum of their own. Consider putting these in the same
# hidden forum.

_FORUMS_GENERAL_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_BEGIN_SITE_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_BEGIN_PROOF_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_CONTENT_PROVIDERS_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_POST_PROCESSORS_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
# These forums aren't involved in the operation of the site,
# they just show up in various links.

_AUTO_POST_TO_PROJECT_TOPIC=false
# If you set this to `true`, the code will automatically add a post to a
# project's discussion topic when the project undergoes certain events.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Miscellaneous
# -------------

_USE_SECURE_COOKIES=false
# If set to `true`, browsers are instructed to only send the cookie over secure
# connections. Your _CODE_URL *must* be using https:// for this to work!

_TESTING=false
# So far, the effects of setting this to `true` are:
# (1) It prevents email messages from being sent. Instead, the site shows a
#     copy of the message that would have been sent. See pinc/maybe_mail.inc.
# (2) metarefresh delays by 15 seconds.

_ALERT_MESSAGE=
# If set, this message will show a bar with this message below the navbar.
# This can contain links and other HTML, but do not include block-level
# tags like <p>.

_MAINTENANCE=false
# Setting this to `true` prevents pages from loading for anyone but already-
# authenticated site admins and shows a "site down for maintenance" message.

_MAINTENANCE_MESSAGE=
# If set, this *additional* message will be displayed to the user when the
# site is in maintenance mode. It can contain HTML (such as links to a blog
# or some other site giving status on the maintenance) but must not contain
# single quotes (').

_ORDINARY_USERS_CAN_SEE_QUEUE_SETTINGS=true
# Setting this to `true` means that all users can see the project_selector,
# projects_target, pages_target, and comments columns on the Release Queue pages.
# Setting it to `false` means that only site admins and project facilitators
# can see those columns. (This was the hard-coded behavior in R200609 and
# earlier.)

_GETTEXT_LOCALES_DIR=/usr/share/locale
# The system's locale directory.  Usually /usr/share/locale

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Development and Deployment parameters
# -------------------------------------

# These settings specify how code is pulled from source control and
# deployed on this server. If you are installing a released package
# and not pulling from source control, you can ignore this section.

# $TAG is the git branch or tag to extract files from the repository.
TAG=master

# $GROUP is the name of the group that will group-own the files.
GROUP=$USER

# $SHIFT_TO_LIVE should be 'yes', 'no', or 'prompt'.
# If it's 'yes', or it's 'prompt' and the user answers 'y',
# then $_CODE_DIR.new will be moved to $_CODE_DIR.
# If that directory already exists, it will first be renamed as
#     $_CODE_DIR.bak
# and if *that* directory already exists, it will be REMOVED.
SHIFT_TO_LIVE=prompt

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Automatic executable detection
# ------------------------------

# The system relies on several standard executables. This section attempts
# to automatically determine where they are and configure them. If the
# system is unable to determine one when one is required, it will output
# and error message and die with instructions on how to fix it.

# The location of the command-line version of PHP.
# We use this in pinc/forum_interface.inc to invoke some phpBB functions
# without including the phpBB namespace into ours.
_PHP_CLI_EXECUTABLE=
if [ ! -x "$_PHP_CLI_EXECUTABLE" ]; then
    if exe_path=$(command -v php); then
        _PHP_CLI_EXECUTABLE=$exe_path
    else
        echo "ERROR: Unable to find command-line version of PHP."
        echo "       Edit the configuration file and set _PHP_CLI_EXECUTABLE."
        exit 1
    fi
fi

# The location of xgettext executable.
_XGETTEXT_EXECUTABLE=
if [ ! -x "$_XGETTEXT_EXECUTABLE" ]; then
    if exe_path=$(command -v xgettext); then
        _XGETTEXT_EXECUTABLE=$exe_path
    else
        echo "WARNING: Unable to find xgettext, site translation functionality"
        echo "         may be limited."
    fi
fi

# Automatically determine an installed program (with parameters) to dump
# the contents of a URL. The program is then used in SETUP/dp.cron.
_URL_DUMP_PROGRAM=
if [ ! -x "$_URL_DUMP_PROGRAM" ]; then
    # No program explicitly specified, attempt to find: wget, curl, lynx
    if exe_path=$(command -v wget); then
        _URL_DUMP_PROGRAM="$exe_path --quiet --tries=1 --timeout=0 -O-"
    else
        if exe_path=$(command -v curl); then
            _URL_DUMP_PROGRAM="$exe_path --silent"
        else
            if exe_path=$(command -v lynx); then
                _URL_DUMP_PROGRAM="$exe_path -source"
            else
                echo "ERROR: No program found to dump URLs."
                echo "       Edit the configuration file and set _URL_DUMP_PROGRAM."
                exit 1
            fi
        fi
    fi
fi
