# Upgrading

This document covers upgrading an existing installation of DP that
contains data you want to bring forward to work with the new code
release.

## Back up your data
Before doing an upgrade, back up your data. The most important data
is the one in your database. You can dump that data from within the
existing installation with:
```bash
c/SETUP/dump_db_schema > somewhere/pre_upgrade_db_schema
```

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

## Upgrade your database schema and data

There are scripts to upgrade your installation from one version to another.
Each version comes with its own directory of upgrade scripts. Find the version
you are currently running and walk through all of the upgrade scripts for
every version between that and the latest.

To run the upgrade scripts, you must cd into the directory and invoke the
scripts from there. E.g.:

```bash
cd c/SETUP/upgrade/06/
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
$maintenance_override = TRUE;
```

### Upgrading from release 1.3 or earlier
Sorry, there's no automated upgrade mechanism. If you post
to the 'DP Site Code' forum, we might be able to help you.

### Upgrading from release 1.4
Run the scripts in the following directories in order

* c/SETUP/upgrade/05/
* c/SETUP/upgrade/06/
* c/SETUP/upgrade/07/
* c/SETUP/upgrade/08/
* c/SETUP/upgrade/09/
* c/SETUP/upgrade/10/
* c/SETUP/upgrade/11/
* c/SETUP/upgrade/12/
* c/SETUP/upgrade/13/

### Upgrading from release 1.5
Run the scripts in the following directories in order

* c/SETUP/upgrade/06/
* c/SETUP/upgrade/07/
* c/SETUP/upgrade/08/
* c/SETUP/upgrade/09/
* c/SETUP/upgrade/10/
* c/SETUP/upgrade/11/
* c/SETUP/upgrade/12/
* c/SETUP/upgrade/13/

### Upgrading from release 1.6
Run the scripts in the following directories in order

* c/SETUP/upgrade/07/
* c/SETUP/upgrade/08/
* c/SETUP/upgrade/09/
* c/SETUP/upgrade/10/
* c/SETUP/upgrade/11/
* c/SETUP/upgrade/12/
* c/SETUP/upgrade/13/

### Upgrading from release R200609
Run the scripts in the following directories in order

* c/SETUP/upgrade/08/
* c/SETUP/upgrade/09/
* c/SETUP/upgrade/10/
* c/SETUP/upgrade/11/
* c/SETUP/upgrade/12/
* c/SETUP/upgrade/13/

### Upgrading from release R201601
Run the scripts in the following directories in order

* c/SETUP/upgrade/09/
* c/SETUP/upgrade/10/
* c/SETUP/upgrade/11/
* c/SETUP/upgrade/12/
* c/SETUP/upgrade/13/

### Upgrading from release R201701
Run the scripts in the following directories in order

* c/SETUP/upgrade/10/
* c/SETUP/upgrade/11/
* c/SETUP/upgrade/12/
* c/SETUP/upgrade/13/

### Upgrading from release R201707
Run the scripts in the following directories in order

* c/SETUP/upgrade/11/
* c/SETUP/upgrade/12/
* c/SETUP/upgrade/13/

### Upgrading from release R201802
Run the scripts in the following directories in order

* c/SETUP/upgrade/12/
* c/SETUP/upgrade/13/

### Upgrading from release R201903
Run the scripts in the following directories in order

* c/SETUP/upgrade/13/

## Upgrade from phpBB2 to phpBB3
If your prior version was running phpBB2, upgrade to phpBB3.
See `phpbb3-conversion.txt` for steps on how to do this.

## Install the modified `dp.cron`
Install the modified `dp.cron`.
