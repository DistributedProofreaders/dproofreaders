# Installation Instructions

This file contains instructions for installing/upgrading the Distributed
Proofreaders website code. If you are upgrading an existing installation
see [UPGRADE.md](UPGRADE.md).

The instructions in this file assume that you're using a release tarball from
[DP's Project Page on github.com](https://github.com/DistributedProofreaders/dproofreaders).
If you're installing/upgrading from source control, it's trickier.

In case of problems, please post to the [DP Site Code](http://www.pgdp.net/phpBB3/viewforum.php?f=32)
forum at the [primary DP site](https://www.pgdp.net/c).

## Middleware version requirements
The following lists supported versions for the four primary middleware
components.

### PHP
PHP version 7.4 is the minimum supported version. Limited testing has been done
on PHP 8.0 and 8.1.

The following PHP extensions are required. They are listed below with their
Ubuntu system package names.
* Internationalization - php-intl
* mbstring - php-mbstring
* memcached - php-memcached (to enable API rate limiting)
* MySQL - php-mysql
* xml - php-xml (used by packages installed with composer)
* zip - php-zip

### Composer
PHP package dependencies are managed using [Composer](https://getcomposer.org/).
You need a recent version of Composer installed to manage the relevant PHP
package dependencies.

### MySQL
MySQL version 5.7 is the minimum supported version. MySQL 8.x will also work.

MariaDB version 10.2 and later should also work but has not been tested.

### phpBB
[phpBB](https://www.phpbb.com) 3.3 is the minimum supported version.

For phpBB to work with the DP code, you must disable superglobal checking.
In the phpBB code edit:

* 3.3: `config/default/container/parameters.yml`

change `core.disable_super_globals` to `false`, and flush the phpbb cache.

## Distro support
These middleware components match the following major distribution releases:
* Ubuntu 18.04, Bionic (with PHP 7.4 upgrade)
* Ubuntu 20.04, Bionic
* RHEL / CentOS 7.x family (with PHP 7.4 and MariaDB 10.2 or later upgrade)
* RHEL / CentOS 8.x family

## Browser support
The following are the lowest known supported browser versions for the code:
* Chrome 54
* Firefox 52
* Microsoft Edge
* Opera 41
* Safari 11

Internet Explorer is not supported.

## Installing from scratch
This section assumes that you don't have an existing installation of DP.
If you're upgrading from a previous version, see [UPGRADE.md](UPGRADE.md).

When we say to install and/or configure some third-party software, that's
assuming it isn't already.

### Install a web server
Any web server that supports PHP should work, although all testing has
been done using Apache 2. All known deployments, including pgdp.net,
are using Apache 2 as well.

See `apache2.conf.example` for an example Apache config file, including
examples on enabling page compression and caching.

### Install PHP
* Enable YAZ if you want to be able to do external catalog searches when
  creating projects.
* Enable the CLI for running cron jobs and future upgrade scripts.
* Configure the PHP [opcode cache](https://www.php.net/manual/en/opcache.configuration.php)
  to improve site performance. Allocate 128MB to the cache if possible.

Consider the following settings in `php.ini`:
* `session.gc_probability = 1` (or something other than zero)
  Needed to trigger garbage collection in 'sessions' table
  if `_USE_PHP_SESSIONS=TRUE` in your DP config file.

### Install MySQL
In the examples in this document, we will assume that the MySQL server
is on "localhost", i.e. the same host on which you are running these
commands, and the same host that runs the webserver.

#### MySQL tunings
The following are some suggested MySQL system tunings based on experiences
at pgdp.net. You should consider them initial guidelines and not absolutes.

The DP code uses a combination of InnoDB and MyISAM tables, specifically all
tables are InnoDB except the per-project tables that use MyISAM. When
allocating memory to the various buffers, you only need ~256M to the MyISAM
`key_buffer_size`. For the `innodb_buffer_pool_size`, consider 4G or 50% of
the system memory, whichever is smaller.

Because each project is in its own table, the total number of database tables
can get rather high. You may need to increase the size of the table definition
cache (`table_definition_cache`) -- pgdp.net is using 16384 for this value.
Increasing the number of open files (`open_files_limit`) and the table cache
(`table_open_cache`) are also useful, although care must be taken to not
increase these more than the MySQL daemon can open based on kernel limits.
Consult the MySQL documentation for more information.

### Install gettext and xgettext
If you want to localize the site messages, install gettext and xgettext.

### Install aspell
The code uses aspell for the spellchecker in the proofreading interface.
aspell 0.60 or later is required.

### Install optional components

The following components are optional and provide additional functionality.

#### Anti-Virus Scanner (ClamAV)
If an anti-virus scanner is installed and configured, the code will use it to
test uploaded files for viruses. Any command-line scanner that returns 0 on
pass and 1 on failure will work. pgdp.net uses [ClamAV](https://www.clamav.net/).

To configure the software to use the virus scanner, set `_ANTIVIRUS_EXECUTABLE`
in `configuration.sh`.

The ClamAV CLI client, `clamscan`, can be slow to scan files due to its
start-up time. To scan files faster, install and run the
[daemon](https://www.clamav.net/documents/usage#daemon) and use `clamdscan`
as your virus scanner executable.

#### wdiff
WordCheck uses [wdiff](http://www.gnu.org/software/wdiff/wdiff.html)
to assist Project Managers in detecting stealth scannos ("Suggestions from
diff analysis" in `c/tools/project_manager/show_project_stealth_scannos.php`).
If `wdiff` is not installed this one tool will fail but the rest of
WordCheck will operate correctly.

#### WikiHiero
To enable the hieroglyph transliteration tool, download
[WikiHiero](http://aoineko.free.fr/) and extract it somewhere in your web
server's document hierarchy. WikiHiero 0.2.13 needs to be patched with
`wikihiero-0.2.13.patch` to make it work with PHP 7.0.

For example:
```bash
# Download and extract
wget http://aoineko.free.fr/wikihiero.zip
unzip -d /var/www/htdocs/wikihiero wikihiero.zip
rm wikihiero.zip

# Patch
cd /var/www/htdocs/wikihiero
dos2unix wikihiero.php
patch -p1 < /path/to/wikihiero-0.2.13.patch
unix2dos wikihiero.php
```

Then set `_WIKIHIERO_DIR` and `_WIKIHIERO_URL` in your `configuration.sh` file
discussed below.

#### pngcheck
Project Quick Check will use `pngcheck` for PNG image validation if it is
installed.

### Configure MySQL
Choose names for various MySQL items:
* the DP database
* the DP user (to handle all DP and phpBB queries)
* the DP user's password

Don't pick a password that contains an apostrophe (single-quote),
as it confuses the code.

In the examples in this document, we will use the following:
* `dp_db`
* `dp_user`
* `dp_password`

You may wish to choose names that are harder for others to guess
(especially for the password)!

Set up MySQL to create the database and user.
There are various ways to do each of these.
We'll show how to do it using the MySQL client.

Connect to the MySQL server as the root user, or any sufficiently
powerful user.
```bash
mysql -h localhost -u root -p
```

Create the database.
```
CREATE DATABASE dp_db CHARACTER SET utf8mb4;
```

Create the user. (See MySQL Manual 5.5.4 Adding New Users to MySQL.)
```
CREATE USER dp_user@localhost IDENTIFIED BY 'dp_password';
GRANT ALL ON dp_db.* TO dp_user@localhost;
```

Exit from the MySQL client.
```
quit;
```

### Install and configure phpBB
phpBB is most easily installed in your document root as `phpbb/`
The phpBB tables need to be installed in the same database as the DP
tables, or be in a separate database in the same MySQL instance as the
DP tables with the same authentication information.

#### HTML in Site Description (optional)
pgdp.net uses HTML in the Site Description for the board configuration to point
back to the Activity Hub, Wiki, and Blog. phpBB3 doesn't allow HTML in the
site description without a code change.

We patched `phpBB3/includes/functions.php` to allow HTML in the site
description. A patch file is included as `phpbb3-functions.php.patch`.

To use it, cd into the phpBB code directory and run:

    patch -p0 < path_to_SETUP/phpbb3-functions.php.patch

#### PM contents in email notification (optional)
pgdp.net members like having a PM's contents in the email notification from the
forum. For phpBB 3.0, the Prime Notify extension does this. For phpBB 3.2,
we patch `phpBB3/phpbb/notification/types/pm.php` using `phpbb32-pm.php.patch`.

To use it, cd into the phpBB code directory and run:

    patch -p0 < path_to_SETUP/phpbb32-pm.php.patch

### Create phpBB categories and forums
When the site is operational, each team and each project will get a
discussion topic created by the code. You should create a forum to
house the team topics, and four forums for project topics, one for each
of:
* projects not yet released for proofreading
* projects released for proofreading
* projects in post-processing
* projects that have been posted

The code also assumes the existence of a few more forums, not in the
operational sense above, but just to provides helpful links.
* general
* beginners' site Q&A
* beginners' proofreading Q&A
* content providers
* post-processing

When you create these forums, you can give them any name or description
you want. After, take note of their ids -- you'll need these when you
configure the DP code.

Other than that, you can create whatever categories/forums/topics you
like. As a suggestion only, here are some of the categories and forums
at www.pgdp.net (the ones referred to above are prefixed with `**`):

    For Beginners
        ** Common Site Q&A
        ** Common Proofreading Q&A
    Site
        ** General
        -- Future Features
        -- Promotion
        -- Documentation
        -- Site-Related Polls
    Activities
        ** Providing Content
        -- Managing Projects
        -- Mentoring
        ** Post-Processing
    Projects
        ** Projects Waiting
        ** Projects Being Proofread or Formatted
        ** Projects Being Post-Processed or Verified
        ** Archive of Posted Projects
    Community
        -- DP Culture and History
        ** Team Talk
        -- Fun Polls
        -- Project Gutenberg
        -- Everything Else (except DP)
    Helpful Software
        -- Windows
        -- Mac
        -- *nix
        -- Cross-Platform
        -- Tool Development
        -- DP Site Code

### Unpack the DP code
Somewhere within your web server's document hierarchy (possibly,
though not necessarily, in the document root) unpack the DP code.
This expands into a directory named 'c'.

### Create other directories
Create some additional directories under your web server's document
hierarchy:

    projects

    d
    d/locale
    d/stats
    d/stats/automodify_logs
    d/teams
    d/teams/avatar
    d/teams/icon
    d/pg
    d/xmlfeeds

And create the following temporary directory to house WordCheck temp files:

    /tmp/sp_check

These should have permissions `drwxrwsr-x` and have the owner & group
of the user that the DP code runs as (probably 'nobody' or 'www-data').

### Create the directory for project file uploads
In order to populate projects with images and initial text, the images
and text must reside on the server. This can be done by users via the
web with the built-in `remote_file_manager.php`, or via FTP/scp, or
both. In either case, a directory outside the web space, but writeable
by the web server, is required.

If using `remote_file_manager.php`, you can create this directory wherever
it makes sense to do so, although for security reasons it is strongly
recommended that it be outside of your web space. Ensure the directory
permissions and/or ownership allow the web server full access to the
directory and its contents.

If using FTP or scp, create a system user and have the uploads
directory located inside that user's home directory. Ensure that
new files and directories within this space have permissions set
to 777 so that the web server has full access to them.

See the 'Uploading and Creating Projects' section of `configuration.sh`
for more details.

### Install DP code PHP package dependencies
Using composer, install the package dependencies. From the root of
your DP code checkout:

```bash
composer install --no-dev
```

### Configure the DP code (with site-specific settings)
Make an editable copy of `c/SETUP/configuration.sh`, and put it outside
your webserver's document hierarchy.

Modify your copy of `configuration.sh` as appropriate. Details about each
parameter are included in `configuration.sh`. Run:
```bash
c/SETUP/configure path-to-modified-configuration.sh path-to-code-dir
```
The configure script will use your modified `configuration.sh` to update
the DP code with your site-specific settings.

### Create the tables of the DP database
```bash
cd c/SETUP
php -f install_db.php
```

### Move SETUP directory outside your webserver's document hierarchy
The `c/SETUP/` directory is only needed for site configuration. **As a
security measure, move it out of your webserver's document hierarchy.**
Don't delete it as you may want to use its contents again to
reconfigure the site.

### At this point, your DP site should be functional
Try visiting the site in your browser and registering as a new user
to ensure that works.

You may want to redirect/rewrite requests for foo/ to foo/c/.
You could just create a foo/index.html that redirects.

### Install MediaWiki (optional)
None of the DP code relies on the presence of a wiki, MediaWiki or
otherwise, but most DP sites have some form of wiki for user
contributions, coordination, documentation, etc. If you have a wiki,
set `_WIKI_URL` in `configuration.sh` to have a link show up in the navbar.

pgdp.net uses MediaWiki and the `MediaWiki_extensions/` directory
includes some useful extensions you might want to use.

It's also suggested that you use
[MediaWiki_PHPBB_Auth plugin](https://github.com/Digitalroot/MediaWiki_PHPBB_Auth)
to have the wiki authenticate against phpBB for a single source of user
credentials.

WARNING: If your site ran phpBB 2.x with the MediaWiki_PHPBB_Auth extension
at some point in the past, you must patch the extension after installing it!
The extension for phpBB 2.x created MediaWiki usernames that matched the
capitalization of those from phpBB. The extension for phpBB 3.0 uses the new
`username_clean` column which, among other things, lowercases the phpBB
usernames in the wiki. Without the patch, users with capital letters in their
names will get a new wiki account differing only in capitalization upon log-in.
Apply the included `MediaWiki_PHPBB_Auth.patch` to the `extensions/`
subdirectory after installing the extension.

### Define a site administrator
The code is based on users having particular roles. To manage these,
a site administrator is needed. Assuming you are administering the
site and have registered yourself as a new user, sign into the `mysql` client
and add an entry in the `usersettings` table for the username you registered
with `setting='sitemanager'` and `value='yes'`.

From the mysql command line, this would be:
```
mysql> INSERT INTO usersettings
       SET username='your_username', setting='sitemanager', value='yes';
```

### Consult Site Admin Notes
The `site_admin_notes.txt` contains information for site admins such as
configuring rounds, defining queues, etc.

### Manage Character Suites
You may want to enable additional character suites. See [UNICODE.md](UNICODE.md)
for more information.

### Set up project archiving (optional)
If you want to enable project archiving, see [ARCHIVING.md](ARCHIVING.md).

### Install the modified `dp.cron`
`dp.cron` contains entries for various processes necessary for site
statistics and project archiving, as well as managing the project release
queues, various user notifications, and the like.

Check that the values inserted by the DP configuration script are correct,
then install the crontab onto your system as an appropriate user.
