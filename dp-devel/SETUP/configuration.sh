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

# $TAG is the CVS-tag used to extract files from the repository.
TAG=HEAD

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

# ----------------------------------------------------------------------

# At pgdp.net, Project Managers are given access to a shared ftp-only
# "uploads" account. Each PM creates a personal directory (named
# according to their DP login id) within the shared account's home
# directory.

_UPLOADS_DIR=/home/dpscans
_UPLOADS_HOST=www.example.org
_UPLOADS_ACCOUNT=dpscans
_UPLOADS_PASSWORD=PICK_A_PASSWORD

# The _DIR variable is important because it tells the code where to load
# project information (text and images) from. The others are just echoed
# as reminders to the user. If you'd rather not have that information
# echoed on-screen, or if you want to use some other mechanism to get
# project data into $_UPLOADS_DIR, you can leave the other 3 variables
# with empty or bogus values.

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

# ----------------------------------------------------------------------

# In the proofing interface, the Spell-Checking functionality relies on
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
# appear in the proofing interface.
# If you haven't installed wikihiero, leave them empty.

# ----------------------------------------------------------------------

_WIKI_URL=$base_url/wiki

# If there's a wiki that you want your users to use, set this variable
# to its URL, and a link will appear on the navigation bar.
# If you don't have a wiki, set this to the empty string.

# ----------------------------------------------------------------------

# Certain parts of the DP code assume that you have phpBB (or something
# pretty similar) installed.

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

# ----------------------------------------------------------------------

_DYN_DIR=$base_dir/d
_DYN_URL=$base_url/d

_JPGRAPH_DIR=$base_dir/jpgraph

# ----------------------------------------------------------------------

_SITE_URL=$_CODE_URL
# You can think of this as the "publishable" HTTP URL for the site.
# So far, it's only used when generating credit lines.
# -- It could be exactly the same as $_CODE_URL.
# -- Or it might be a more memorable or more permanent URL that simply
#    redirects to $_CODE_URL.
# -- Or it might be the address of some site-specific content, perhaps
#    a pre-introduction, which would presumably include a link to $_CODE_URL.
# (DP-US uses the second option, because PG didn't want us to use the first.)

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
