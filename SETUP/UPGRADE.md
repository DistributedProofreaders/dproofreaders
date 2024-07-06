# Upgrading

This document covers upgrading an existing installation of DP that
contains data you want to bring forward to work with the new code
release.

## Disable the site and cron jobs
Generally, it's important that when doing an upgrade _nothing else is
changing your database_ (specifically, the one whose name is bound to
`_DB_NAME` in your `configuration.sh`). To that end, it's best done offline
during a maintenance window.

You can disable the site by putting it into maintenance mode. Set the following
two variables in `pinc/site_vars.php`:
* `$maintenance = true;`
* `$maintenance_message = '';` (optional; will be shown to the user if set)

This will result in all site pages showing a maintenance message to regular
users, a notice to site admins, and all API calls will receive a server error
message.

Disable automated cron jobs by commenting them out in the crontab.

## Back up your data
Before doing an upgrade, back up your data. The most important data
is the one in your database.

You should also back up your `projects/` directory.

## Move the old code aside
Move the old installation directory out of the way. Hang on to it
for a bit as you may need some files from it.

```bash
mv c c.old
```

## Unpack the new DP code
Unpack the new DP code using either `tar -xf` or `unzip`. This creates
a new 'c' hierarchy.

## Configure the new DP code
Follow the 'Installing from scratch' instructions in [INSTALL.md](INSTALL.md).
Note that the modified `configuration.sh` that you used to
configure your old code is probably not sufficient to configure the
new code; however, it does have lots of settings that you should carry
forward to the new config file.

You can use `import_old_configuration.php` to import values from your old
configuration file into the new one included with with this distribution.
Use it like:

```bash
c/SETUP/import_old_configuration.php \
    old_config_file.sh c/SETUP/configuration.sh > new_config_file.sh
```

The script will notify you of parameters in the old configuration file
that are no longer used in the new one, as well as new parameters in
`configuration.sh` that you will want to take a closer look at.

## Update your composer dependencies

Using composer, install the package dependencies. From the root of
your DP code checkout:

```bash
composer install --no-dev
```

## Upgrade your database schema and data

There are scripts to upgrade your installation from one version to another.
Each version comes with its own directory of upgrade scripts. Find the version
you are currently running and walk through all of the upgrade scripts for
every version between that and the latest.

To run the upgrade scripts, you must cd into the directory and invoke the
scripts from there. E.g.:

```bash
cd c/SETUP/upgrade/15/
php -f add_non_activated_users.php
php -f add_pg_books.php
...
```

It's a good idea to log the shell I/O (e.g., using the 'script' command),
as this may help diagnose any problems you have.

The order in which you invoke the scripts shouldn't matter, but alphabetical
order is probably safest, as that's been tested the most.

Each script will print 'Done!' if it completes without fatal errors.

If you have placed the site into maintenance mode the scripts will bail out.
To force the upgrade scripts to run while in maintenance mode, edit the ones
you want to run and add the following line immediately after the opening
`<?php` tag before `base.inc` is included.

```php
$maintenance_override = true;
```

### Upgrading from a release before R202009

If you are upgrading a release before R202009, you must upgrade to
[R202009](https://github.com/DistributedProofreaders/dproofreaders/releases/tag/R202009)
first.

### Upgrading from release R202009
Run the scripts in the following directories in order

* c/SETUP/upgrade/15/
* c/SETUP/upgrade/16/
* c/SETUP/upgrade/17/
* c/SETUP/upgrade/18/
* c/SETUP/upgrade/19/
* c/SETUP/upgrade/20/

### Upgrading from release R202102
Run the scripts in the following directories in order

* c/SETUP/upgrade/16/
* c/SETUP/upgrade/17/
* c/SETUP/upgrade/18/
* c/SETUP/upgrade/19/
* c/SETUP/upgrade/20/

### Upgrading from release R202109
Run the scripts in the following directories in order

* c/SETUP/upgrade/17/
* c/SETUP/upgrade/18/
* c/SETUP/upgrade/19/
* c/SETUP/upgrade/20/

### Upgrading from release R202202
Run the scripts in the following directories in order

* c/SETUP/upgrade/18/
* c/SETUP/upgrade/19/
* c/SETUP/upgrade/20/

### Upgrading from release R202209 or R202303
Run the scripts in the following directories in order

* c/SETUP/upgrade/19/
* c/SETUP/upgrade/20/

### Upgrading from release R202309
Run the scripts in the following directories in order

* c/SETUP/upgrade/20/

## Install the modified `dp.cron`
Install the modified `dp.cron`.

## Re-enable the site and cron jobs
Edit `pinc/site_vars.php` and set `$maintenance = false;` to bring the site
back online.

Also enable the cron jobs that you disabled at the beginning of the upgrade.
