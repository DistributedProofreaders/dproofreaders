# Make an editable copy of this file, put it *outside* your
# server's doc root, and edit that file to configure your DP system.
# ----------------------------------------------------------------------

# Set this file's variables to values that are appropriate for your
# system. In some cases, the setting that already appears here may
# be satisfactory. However, you will definitely need to change any value
# that refers to 'example.org', and any value that's a password.

# This file (or rather, your edited copy of it) is sourced by the scripts
# 'update_from_sf' and 'configure'.  Those are Bourne Shell scripts,
# so you can use any syntax that /bin/sh allows.  However, in typical
# usage, you would merely assign literal values to shell variables.

# Bug/limitation: if a variables value contains an apostrophe, the
# configuration process will not work correctly. (Note that it's fine to
# use apostrophes to delimit string literals; they aren't part of the
# value.) This is unlikely to be a problem outside the "Identifying
# the Site" section.

# Security:
#
# Because this file will contain various passwords, you should be
# careful not to allow it to be seen by unprivileged users. In
# particular, don't put it (or leave it) under your server's doc root.

# ----------------------------------------------------------------------

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

# ----------------------------------------------------------------------

# For a variable whose name ends in _DIR, the value should be an absolute path
# to a directory in the local file system.
# For a variable whose name ends in _URL, the value should be an absolute URL.
# For a DIR/URL pair, the URL should resolve to the DIR.
#
# We don't require a particular arrangement of these directories, but
# we've found it convenient to make most of them siblings (children of
# a base directory), so the following code is slanted in that direction:
# if you choose to use this layout, then you only need to set
# $base_dir and $base_url, and most of the _DIR/_URL variables
# will be set accordingly.
#
base_dir=/home/$USER/public_html
base_url=http://www.example.org/~$USER

_CODE_DIR=$base_dir/c
_CODE_URL=$base_url/c
# The location where the code was installed.
# (It corresponds to 'dp-devel' in the CVS repository,
# and should contain directories such as 'pinc' and 'tools'.)

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

# Something like 'Thank you!\nThe Management' would be good. It will be
# used in the footer of emails from the site. (Does not affect phpBB
# emails; use the Administration Panel to change that.)
_SITE_SIGNOFF="Thank you!\n$_SITE_NAME"

# You can think of this as the "publishable" HTTP URL for the site.
# So far, it's only used when generating credit lines.
# -- It could be exactly the same as $_CODE_URL.
# -- Or it might be a more memorable or more permanent URL that simply
#    redirects to $_CODE_URL.
# -- Or it might be the address of some site-specific content, perhaps
#    a pre-introduction, which would presumably include a link to $_CODE_URL.
# (DP-US uses the second option, because PG didn't want us to use the first.)
_SITE_URL=$_CODE_URL

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
# directory. This has since been replaced with the project_upload.php
# script.

# The location where project information (text and images) are written
# to by project_upload.php and read from for loading into projects.
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

_EXTERNAL_CATALOG_LOCATOR='z3950.loc.gov:7090/Voyager'

# When you Create a Project, you have the option of searching an
# external (Z39.50-enabled) catalog for bibliographic information.
# This variable specifies the catalog to search.

# It should be a locator in the form 'host[:port][/database]', suitable
# for passing to 'yaz_connect'.

# To disable external search at project-creation time, leave this
# variable empty.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Proofreading Projects
# -----------------

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

_PUBLIC_PAGE_DETAILS=FALSE

# When a user proofs a page, the site records their username, and can
# display this in various places, including the page details table and
# concatenated text files.  This setting controls the visibility of
# those names.
# If TRUE, all logged-in users can see all names for all pages in all
#     projects.
# If FALSE, the proofreader names for a given page in a given project
#     are visible only to:
#     --- Site Administrators and Project Facilitators;
#     --- the project's PM, PPer, and PPVer; and
#     --- any other user that worked on the same page.

# ----------------------------------------------------------------------

# In the proofreading interface, the Spell-Checking functionality relies on
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

_WIKIHIERO_DIR=$base_dir/wikihiero
_WIKIHIERO_URL=$base_url/wikihiero

# If you're dealing with text containing Egyptian hieroglyphs, you may
# want to install wikihiero to help with the transcription. If so, set
# these variables to the location you installed it, and a link will
# appear in the proofreading interface.
# If you haven't installed wikihiero, leave them empty.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Communication/Discussion
# ------------------------

_BLOG_URL=URL_OF_YOUR_BLOG

_WIKI_URL=$base_url/wiki

# If there's a wiki or a blog that you want your users to use, set the
# above variables to their URLs and links will appear on the navigation
# bar. If you don't have a blog or wiki, set either of both of them to
# the empty string, and they won't appear in the navigation bar.

# ----------------------------------------------------------------------

# Certain parts of the DP code assume that you have phpBB (or something
# pretty similar) installed.

_PHPBB_VERSION=2
_PHPBB_TABLE_PREFIX=phpbb

_FORUMS_DIR=$base_dir/phpBB2
_FORUMS_URL=$base_url/phpBB2

_FORUMS_TEAMS_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
# Each team gets an automatically-created discussion topic in this forum

_FORUMS_PROJECT_WAITING_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_PROJECT_AVAIL_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_PROJECT_PP_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_PROJECT_POSTED_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
# Each project gets an automatically-created discussion topic, which
# moves around among these 4 forums, depending on its state.
# (The exact correspondence is defined in pinc/project_states.inc.)

_FORUMS_GENERAL_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_BEGIN_SITE_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_BEGIN_PROOF_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_CONTENT_PROVIDERS_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
_FORUMS_POST_PROCESSORS_IDX=THE_INDEX_NUMBER_OF_THIS_FORUM
# These forums aren't involved in the operation of the site,
# they just show up in various links.

_AUTO_POST_TO_PROJECT_TOPIC=FALSE
# If you set this to TRUE, the code will automatically add a post to a
# project's discussion topic when the project undergoes certain events.

# XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

# Miscellaneous
# -------------

_PHP_CLI_EXECUTABLE=/usr/local/bin/php
# The location of the command-line version of PHP.
# We use this in SETUP/dp.cron for some cron jobs,
# and in pinc/forum_interface.inc to invoke some phpBB functions
# without including the phpBB namespace into ours.

# ----------------------------------------------------------------------

_DYN_DIR=$base_dir/d
_DYN_URL=$base_url/d

# This directory houses two classes of DP-related files...
#
# Optional user-supplied files:
# (They don't have to exist, but the code will use them if they do.)
#
# --- $_DYN_DIR/stage_icons/$stage_id.jpg
#         A small image to use as an icon for a given stage.
#         (Currently only used on the activity hub.)
#         $stage can be the id of any stage (i.e., round or pool).
#         
# --- $_DYN_DIR/header_images/$stage_id.jpg
#         An image to show at the top of a given site-page.
#
# --- $_DYN_DIR/news_header_images/$page.jpg
#         An image to show at the top of the "news" section of a given
#         site-page. $page can be FRONT, HUB, FAQ, STATS, or a stage-id.
#
#
# Files created by the code:
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
# --- $_DYN_DIR/stats/faq_data.inc
#         Generated PHP code that defines the $faq_data array.
#
# --- $_DYN_DIR/teams/avatar/*
# --- $_DYN_DIR/teams/icon/*
#         Avatar and icon images for teams.
#
# --- $_DYN_DIR/xmlfeeds/*.xml
#         News feeds for the site.

# ----------------------------------------------------------------------

_JPGRAPH_DIR=$base_dir/jpgraph
# The DP code can make efficient use of the jpgraph cache.
# See jpgraph documentation on how to enable the cache.


_DB_SERVER=localhost
_DB_USER=PICK_A_USER_NAME
_DB_PASSWORD=PICK_A_HARD_PASSWORD
_DB_NAME=PICK_A_DB_NAME
_ARCHIVE_DB_NAME=PICK_ANOTHER_DB_NAME

_ARCHIVE_PROJECTS_DIR=$base_dir/archive

_NO_REPLY_EMAIL_ADDR=no-reply@example.org
_GENERAL_HELP_EMAIL_ADDR=dphelp@example.org
_DB_REQUESTS_EMAIL_ADDR=db-requests@example.org
_PPV_REPORTING_EMAIL_ADDR=ppv-reports@example.org
_PROMOTION_REQUESTS_EMAIL_ADDR=dp-promote@example.org
_IMAGE_SOURCES_EMAIL_ADDR=ism@example.org

_USE_PHP_SESSIONS=TRUE
# If set to TRUE, PHP sessions are used to track user preferences, etc;
# if FALSE, the original DP cookie system is used.

_COOKIE_ENCRYPTION_KEY=A_LONG_STRING_OF_GIBBERISH
# You only need to define this if $_USE_PHP_SESSIONS is FALSE.

_TESTING=TRUE
# So far, the effects of setting this to TRUE are:
# (1) It prevents email messages from being sent. Instead, the site shows a
#     copy of the message that would have been sent. See pinc/maybe_mail.inc.
# (2) metarefresh delays by 15 seconds.

_MAINTENANCE=0
# So far, setting this to TRUE prevents the front page from loading
# (displaying a 'back soon' message) for anyone but admins;
# but bookmarks to interior pages are still live for everyone.
# (So maybe not that useful.)

_METADATA=FALSE
# This is a flag to allow the still-developing metadata functionality
# to be active or not.

_CORRECTIONS=FALSE
# Similarly for the corrections-after-posting facility.

_ORDINARY_USERS_CAN_SEE_QUEUE_SETTINGS=TRUE
# Setting this to TRUE means that all users can see the project_selector,
# release_criterion, and comments columns on the Release Queue pages.
# Setting it to FALSE means that only site admins and project facilitators
# can see those columns. (This was the hard-coded behavior in R200609 and
# earlier.)

_CHARSET='ISO-8859-1'
# The charset used by the site, which is applied to all
# relevant pages on the site

_WRITEBIGTABLE=TRUE
_READBIGTABLE=FALSE
# For staged transition to all-in-one project_pages table.


_XGETTEXT_EXECUTABLE=/usr/bin/xgettext
# The location of xgettext executable.

_GETTEXT_LOCALES_DIR=/usr/share/locale
# The system's locale directory.  Usually /usr/share/locale

_JPGRAPH_FONT_FACE=2
_JPGRAPH_FONT_STYLE=9002
# Font face and style values for JpGraph graphs. For possible values, see
# $_JPGRAPH_DIR/src/jpgraph.php (specifically, the FF_ and FS_ defines).

# ----------------------------------------------------------------------
